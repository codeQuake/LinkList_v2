{include file='documentHeader'}

<head>
    <title>{$link->getTitle()|language} - {PAGE_TITLE}</title>    
	{include file='headInclude' sandbox=false}
</head>

<body id="tpl{$templateName|ucfirst}">


    {include file='header' sidebarOrientation='left'}
    <header class="boxHeadline">
        <hgroup>
            <h1>{$link->getTitle()|language}</h1>
        </hgroup>
    </header>


{include file='footer' sandbox=false}
</body>
</html>