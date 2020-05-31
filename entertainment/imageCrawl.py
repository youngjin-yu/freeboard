
from bs4 import BeautifulSoup
from urllib.request import urlopen

url='https://search.naver.com/search.naver?sm=tab_hty.top&where=image&query=%EC%97%AC%ED%96%89'
html=urlopen(url).read()
soup=BeautifulSoup(html,'html.parser')

images = soup.find_all(class_="_img")

for image in images:
    print(image['data-source'])
    print()