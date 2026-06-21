import re
import os
root = os.path.abspath(os.path.join(os.path.dirname(__file__), '.'))
pattern = re.compile(r"__\(\s*['\"]admin\.([a-zA-Z0-9_]+)['\"]")
keys = set()
for dirpath, dirs, files in os.walk(os.path.join(root, 'resources', 'views', 'admin')):
    for f in files:
        if f.endswith('.blade.php') or f.endswith('.php'):
            path = os.path.join(dirpath, f)
            with open(path, encoding='utf-8', errors='ignore') as fh:
                text = fh.read()
            keys.update(pattern.findall(text))
for dirpath, dirs, files in os.walk(os.path.join(root, 'app')):
    for f in files:
        if f.endswith('.php'):
            path = os.path.join(dirpath, f)
            with open(path, encoding='utf-8', errors='ignore') as fh:
                text = fh.read()
            keys.update(pattern.findall(text))
langdir = os.path.join(root, 'lang', 'admin')
res = {}
for lang in ['en', 'hi', 'gu']:
    p = os.path.join(langdir, f'{lang}.php')
    out = set()
    if os.path.exists(p):
        txt = open(p, encoding='utf-8', errors='ignore').read()
        m = re.search(r'return\s*\[([\s\S]*)\];', txt)
        if m:
            body = m.group(1)
            for mm in re.finditer(r"['\"]([a-zA-Z0-9_]+)['\"]\s*=>", body):
                out.add(mm.group(1))
    res[lang] = out
missing = {lang: sorted([k for k in keys if k not in res[lang]]) for lang in res}
print('used_keys_count', len(keys))
print('missing_counts', {lang: len(v) for lang, v in missing.items()})
for lang, v in missing.items():
    if v:
        print('---', lang, 'missing---')
        print('\n'.join(v))
