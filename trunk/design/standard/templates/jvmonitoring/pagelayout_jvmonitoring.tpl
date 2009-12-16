{def $sitename = ezini('SiteSettings', 'SiteName', 'site.ini')}
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<title>{"eZ Publish application monitoring for '%sitename'"|i18n('extension/jvmonitoring', '', hash('%sitename', $sitename))}</title>
	<link rel="stylesheet" type="text/css" href={'stylesheets/jvmonitoring.css'|ezdesign} media="all" charset="utf-8" />
</head>
<body>
{$module_result.content}

<!--DEBUG_REPORT-->
</body>
</html>