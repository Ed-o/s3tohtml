# s3tohtml
A php server to allow browsing of an AWS S3 bucket via web browser

## Why do we need this ?
If you have an S3 bucket with files inside.  For example a dumping area for HTML reports from an automated program, then this will allow you to browse and view them.  
Would you not just use the built in S3 web site browser ?  
This tool is for internal company use.  
I do not want the reports on an open IP address.  I also want to be able to add security (like a login) if needed.  
This site can be located on an internal IP address (10.x.x.x or 192.168.x.x or 172.24.x.x).  
If you want to, you could also put it into a docker type container.  

## How do I install this ?
You will need to have a server with PHP (above 5.6 - I use 7.x)
You need a web server that can redirect to php.  (I use apache httpd and the .htaccess file is written for that).
You need the AWS PHP SDK V3  
 * Note - the AWS docs tell you the best way to install it is using compose. If you do this on an Amazon-Linux repo server you will get SDK V2.x and that does not work. (I use the zip download method)  

## ...and how does it talk to S3 ?
The system is currently designed to use an IAM role.  This is a EC2 role with permissions to read and list objects in a single S3 bucket.  It will be fairly easy to add keys or envirment variables on to allow access.  I will get round to that in the near future (maybe).

## It doesn't work / do what I want / make coffee
This is a first draft.  A project I wrote to fix something for myself.  
If you want new features, feel free to form it and add them (just remember to publish your work for others to use and enjoy)

## Security
Is this secure ?  No.  
It was designed to go on an internal web server on an internal IP address with no external connection.  The data helpd in the S3 is not top secret, or PII, or in anyway dangerous.  If you want security on this system - you add it on.
  
## And thanks go to :

The CSS in the Tables comes direct from : https://www.w3schools.com/css/css_table.asp
The Icons in the biorwser come from the MDI icon set : https://materialdesignicons.com/  
The code to get the S3 SDK stuff - mostly snippets from the AWS docs online
 
  


