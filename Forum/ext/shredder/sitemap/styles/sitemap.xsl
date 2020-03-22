<?xml version="1.0" encoding="UTF-8"?>
<!-- sitemap.xsl -->
<!-- 04/02/14 -->
<xsl:stylesheet version="2.0"
xmlns:html="http://www.w3.org/TR/REC-html40"
xmlns:sitemap="http://www.sitemaps.org/schemas/sitemap/0.9"
xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes" />
<xsl:template match="/">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>XML Sitemap</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
body {
	font-family: Verdana,Arial,Helvetica,sans-serif;
}
a {
	color: black;
}
.centered,
h1 {
	text-align: center;
}
table {
	margin: auto;
}
th,
tr.high {
	background-color: whitesmoke;
}
</style>
</head>
<body>
<xsl:apply-templates></xsl:apply-templates>
</body>
</html>
</xsl:template>

<xsl:template match="sitemap:urlset">
<h1>XML Sitemap</h1>
<p class="centered">Number of URLs in this sitemap: <xsl:value-of select="count(./sitemap:url)"></xsl:value-of></p>
<div id="content">
<table cellpadding="5">
<tr>
<th>URL</th>
<th>Priority</th>
<th>Change<br />Frequency</th>
<th>Last<br />Modified</th>
</tr>
<xsl:variable name="lower" select="'abcdefghijklmnopqrstuvwxyz'" />
<xsl:variable name="upper" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'" />
<xsl:for-each select="./sitemap:url">
<tr>
<xsl:if test="position() mod 2 != 1">
<xsl:attribute name="class">high</xsl:attribute>
</xsl:if>
<td>
<xsl:variable name="itemURL">
<xsl:value-of select="sitemap:loc" />
</xsl:variable>
<a href="{$itemURL}">
<xsl:value-of select="sitemap:loc" />
</a>
</td>
<td class="centered">
<xsl:value-of select="sitemap:priority" />
</td>
<td class="centered">
<xsl:value-of select="concat(translate(substring(sitemap:changefreq,1,1),concat($lower,$upper),concat($upper,$lower)),substring(sitemap:changefreq,2))" />
</td>
<td class="centered">
<xsl:value-of select="concat(substring(sitemap:lastmod,1,10),concat(' ',substring(sitemap:lastmod,12,8)))" />
</td>
</tr>
</xsl:for-each>
</table>
</div>
</xsl:template>

<xsl:template match="sitemap:sitemapindex">
<h1>XML Sitemap Index</h1>
<p class="centered">Number of sitemaps in this sitemap index: <xsl:value-of select="count(./sitemap:sitemap)"></xsl:value-of></p>
<div id="content">
<table cellpadding="5">
<tr>
<th>URL</th>
<th>Last<br />Modified</th>
</tr>
<xsl:for-each select="./sitemap:sitemap">
<tr>
<xsl:if test="position() mod 2 != 1">
<xsl:attribute  name="class">high</xsl:attribute>
</xsl:if>
<td>
<xsl:variable name="itemURL">
<xsl:value-of select="sitemap:loc" />
</xsl:variable>
<a href="{$itemURL}">
<xsl:value-of select="sitemap:loc" />
</a>
</td>
<td>
<xsl:value-of select="concat(substring(sitemap:lastmod,1,10),concat(' ',substring(sitemap:lastmod,12,8)))" />
</td>
</tr>
</xsl:for-each>
</table>
</div>
</xsl:template>
</xsl:stylesheet>