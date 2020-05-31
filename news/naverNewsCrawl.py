import urllib.request
from bs4 import BeautifulSoup

url='https://media.daum.net/entertain/news/variety'
html=urllib.request.urlopen(url).read()
soup=BeautifulSoup(html,'html.parser')


title=soup.find_all(class_='link_txt')
images=soup.find_all(class_="thumb_g")


for i in title:
        print(i.get_text())
        print(i.attrs['href'])
        
# for img in images:
#         print(img.get("src"))+"\n"
print(images[0]) 

for image in images:
        print(image)
