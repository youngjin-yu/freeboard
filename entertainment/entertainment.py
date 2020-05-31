#!/usr/bin/env python3
# Anchor extraction from HTML document
from bs4 import BeautifulSoup
from urllib.request import urlopen

with urlopen('https://www.daum.net') as response:
    soup = BeautifulSoup(response, 'html.parser')
    for anchor in soup.select('a.link_txt'):
        print(anchor.get_text())