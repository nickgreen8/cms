# Nick Green CMS

Author: Nick Green


Website: http://nickgreenweb.co.uk/


E-mail: nick@nickgreenweb.co.uk


## What Is This?

Being created is a very basic and simple CMS that I can use to build and create websites without the use of large scale tools that a user may find hard to use. The aim is to create something that more and more people can use to maintain their own site rather than build them themselves. This means that not only will the site be more easy to maintain but there will be less work as a developer to create a site.

As well as allowing a user to maintain their site more easily, there is a second reason for creating this basic CMS. It will allow for sites to be created much quicker, allowing user's to get there sites sooner and to cut down on development time. By holding a central repo with all elements in, this can be easily maintained and built on for a long time to come and all elements that are created can be used across multipul sites.

## Notes

Before attempting to run this framework, ensure that the log directory (or the directory you would like the logs to be written) has read/write permissions.

### Example Config File

This file should be stored within the config folder.
Filename: config.json

{
	"version": {
		"api": "0.0.1",
		"framework": "0.0.1"
	},
	"database": {
		"type": "mysql",
		"host": "localhost",
		"username": "nick",
		"password": "DevAcc",
		"name": "cms",
		"prefix": "",
		"charset": "utf8"
	},
	"keys": {
		"salting": "",
		"api": "",
		"login": ""
	},
	"debug": false,
	"paths": {
		"absolute": "/Users/NickGreen/Documents/workspace/cms/code/",
		"logs": "/Users/NickGreen/Documents/workspace/cms/code/logs/",
		"directories": {
			"assets": {
				"root": "assets/",
				"images": "image/",
				"videos": "video/",
				"themes": "theme/",
				"documents": {
					"root": "documents/",
					"docs": "doc/",
					"pdfs": "pdf/",
					"spreadsheets": "spreadsheet/",
					"txt": "txt/"
				}
			}
		}
	}
}

## Technology Used

The technologies used within this framework are:
- PHP
- MySQL
- HTML5
- CSS3
- Composer
- JSON
- Twig
- PHPUnit