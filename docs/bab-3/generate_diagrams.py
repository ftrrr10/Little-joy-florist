import os
import re
import zlib
import urllib.request
import urllib.error

# 1. Custom Base64 encoding for PlantUML
def encode_base64(data):
    alphabet = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-_"
    res = ""
    i = 0
    while i < len(data):
        r = [0, 0, 0, 0]
        r[0] = data[i]
        if i + 1 < len(data):
            r[1] = data[i+1]
        if i + 2 < len(data):
            r[2] = data[i+2]
            
        b1 = r[0] >> 2
        b2 = ((r[0] & 0x3) << 4) | (r[1] >> 4)
        b3 = ((r[1] & 0xF) << 2) | (r[2] >> 6)
        b4 = r[2] & 0x3F
        
        res += alphabet[b1]
        res += alphabet[b2]
        if i + 1 < len(data):
            res += alphabet[b3]
        if i + 2 < len(data):
            res += alphabet[b4]
        i += 3
    return res

def encode_plantuml(text):
    zlib_filter = zlib.compressobj(9, zlib.DEFLATED, zlib.MAX_WBITS, zlib.DEF_MEM_LEVEL, 0)
    deflated_data = zlib_filter.compress(text.encode('utf-8'))
    deflated_data += zlib_filter.flush()
    return encode_base64(deflated_data)

def generate_svg(puml_content, output_path):
    encoded = encode_plantuml(puml_content)
    url = f"http://www.plantuml.com/plantuml/svg/{encoded}"
    try:
        req = urllib.request.Request(url, headers={'User-Agent': 'Mozilla/5.0'})
        with urllib.request.urlopen(req) as response:
            svg_data = response.read()
        with open(output_path, 'wb') as f:
            f.write(svg_data)
        print(f"✓ Generated: {os.path.basename(output_path)}")
        return True
    except urllib.error.HTTPError as e:
        print(f"   Failed generating {os.path.basename(output_path)}: HTTP Error {e.code}")
        return False
    except Exception as e:
        print(f"   Failed generating {os.path.basename(output_path)}: {str(e)}")
        return False

def main():
    base_dir = os.path.dirname(os.path.abspath(__file__))
    exported_dir = os.path.join(base_dir, 'exported')
    
    # Create exported directory if not exists
    if not os.path.exists(exported_dir):
        os.makedirs(exported_dir)
        print(f"Created directory: {exported_dir}")
        
    print("Scanning for .puml files...")
    
    puml_files = []
    for root, dirs, files in os.walk(base_dir):
        if 'exported' in root:
            continue
        for file in files:
            if file.endswith('.puml'):
                puml_files.append(os.path.join(root, file))
                
    if not puml_files:
        print("No .puml files found.")
        return
        
    print(f"Found {len(puml_files)} .puml files. Starting generation...")
    
    for puml_path in puml_files:
        print(f"\nProcessing {os.path.relpath(puml_path, base_dir)}:")
        with open(puml_path, 'r', encoding='utf-8') as f:
            content = f.read()
            
        # Match all @startuml ... @enduml blocks
        # Only match horizontal spaces [ \t]* after @startuml
        blocks = re.findall(r'(@startuml[ \t]*([^\n\r]*)(.*?)(?=@enduml)@enduml)', content, re.DOTALL)
        
        if not blocks:
            print(f"  No @startuml blocks found in {os.path.basename(puml_path)}")
            continue
            
        for i, (full_block, name_part, block_content) in enumerate(blocks):
            # Clean name
            name = name_part.strip()
            # If name is empty, or starts with standard puml commands/directives
            if not name or any(name.startswith(x) for x in ['skinparam', 'left', 'right', 'top', 'bottom', 'title', 'newpage', 'split']):
                name = os.path.splitext(os.path.basename(puml_path))[0]
                if len(blocks) > 1:
                    name = f"{name}_{i+1}"
            
            output_path = os.path.join(exported_dir, f"{name}.svg")
            generate_svg(full_block, output_path)

if __name__ == '__main__':
    main()
