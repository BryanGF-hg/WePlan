#!/usr/bin/env python3
"""
Script simple para convertir archivos Markdown (.md) a PDF
Requisitos: pip install markdown pdfkit wkhtmltopdf
"""

import os
import sys
import markdown
import pdfkit

def md_to_pdf(input_file, output_file=None):
    """
    Convierte un archivo Markdown a PDF
    
    Args:
        input_file: Ruta al archivo .md
        output_file: Ruta al archivo .pdf (opcional)
    """
    # Verificar que el archivo existe
    if not os.path.exists(input_file):
        print(f"❌ Error: El archivo '{input_file}' no existe")
        return False
    
    # Verificar extensión .md
    if not input_file.lower().endswith('.md'):
        print(f"⚠️  Advertencia: '{input_file}' no tiene extensión .md")
    
    # Generar nombre de salida si no se proporciona
    if output_file is None:
        output_file = os.path.splitext(input_file)[0] + '.pdf'
    
    try:
        print(f"📖 Leyendo: {input_file}")
        
        # Leer archivo Markdown
        with open(input_file, 'r', encoding='utf-8') as f:
            md_content = f.read()
        
        # Convertir Markdown a HTML
        print("🔧 Convirtiendo Markdown a HTML...")
        html_content = markdown.markdown(md_content)
        
        # Crear HTML completo con CSS básico
        full_html = f"""
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body {{ font-family: Arial, sans-serif; line-height: 1.6; margin: 40px; }}
                h1, h2, h3 {{ color: #333; }}
                code {{ background: #f4f4f4; padding: 2px 6px; border-radius: 3px; }}
                pre {{ background: #f4f4f4; padding: 15px; border-radius: 5px; overflow-x: auto; }}
                blockquote {{ border-left: 4px solid #ccc; padding-left: 15px; color: #666; }}
                table {{ border-collapse: collapse; width: 100%; }}
                th, td {{ border: 1px solid #ddd; padding: 8px; text-align: left; }}
                th {{ background-color: #f2f2f2; }}
            </style>
        </head>
        <body>
            {html_content}
        </body>
        </html>
        """
        
        # Configurar opciones para pdfkit
        options = {
            'page-size': 'A4',
            'margin-top': '0.75in',
            'margin-right': '0.75in',
            'margin-bottom': '0.75in',
            'margin-left': '0.75in',
            'encoding': "UTF-8",
        }
        
        # Convertir HTML a PDF
        print(f"🔄 Generando PDF: {output_file}")
        pdfkit.from_string(full_html, output_file, options=options)
        
        print(f"✅ ¡Listo! PDF guardado en: {output_file}")
        return True
        
    except FileNotFoundError:
        print("❌ Error: No se encontró wkhtmltopdf. Instálalo con:")
        print("   Ubuntu/Debian: sudo apt-get install wkhtmltopdf")
        print("   macOS: brew install wkhtmltopdf")
        return False
    except Exception as e:
        print(f"❌ Error inesperado: {e}")
        return False

def main():
    """Función principal"""
    if len(sys.argv) < 2:
        print("📝 Conversor de Markdown a PDF")
        print("Uso:")
        print(f"  {sys.argv[0]} <archivo.md> [archivo_salida.pdf]")
        print("\nEjemplos:")
        print(f"  {sys.argv[0]} documento.md")
        print(f"  {sys.argv[0]} documento.md salida.pdf")
        print(f"  {sys.argv[0]} *.md  # Para convertir todos los .md")
        return
    
    # Si se usa patrón como *.md
    if '*' in sys.argv[1]:
        import glob
        md_files = glob.glob(sys.argv[1])
        print(f"📁 Encontrados {len(md_files)} archivos .md")
        
        for md_file in md_files:
            print(f"\n{'='*40}")
            md_to_pdf(md_file)
    else:
        # Convertir un solo archivo
        input_file = sys.argv[1]
        output_file = sys.argv[2] if len(sys.argv) > 2 else None
        md_to_pdf(input_file, output_file)

if __name__ == "__main__":
    main()
