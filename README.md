# UrduStemmer
How to use Urdu Stemmer.
After installing xamp server,
0- set memory_limit to atleast 512 m in php.ini
1- Extract project zip file to \wamp\www\
2- Create a database < name: stemmer >  into PHPmyadmin
 ( credentials:  new mysqli("localhost", "root", "", "stemmer"); )
3- Import  \wamp\www\UrduStemmer\DB\stemmer.sql  into database.
4- Enter url : http://localhost/UrduStemmer/ into browser.

For Re-Uploading Gels into Database :
1- Click on the tab "Upload GELs into Database"
2- Write file at \wamp\www\UrduStemmer\DB\processing.txt 
but never change its format.
