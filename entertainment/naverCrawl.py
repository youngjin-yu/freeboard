import urllib.request
from bs4 import BeautifulSoup

url='https://search.naver.com/search.naver?where=news&sm=tab_jum&query=%EC%A0%95%EC%B9%98'
html=urllib.request.urlopen(url).read()
soup=BeautifulSoup(html,'html.parser')

title=soup.find_all(class_='_sp_each_title')

for i in title:
    print(i.attrs['title'])
    print(i.attrs['href'])
    

