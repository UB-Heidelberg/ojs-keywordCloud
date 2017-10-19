{**
 * plugins/blocks/keywordCloud/block.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Keyword cloud block plugin
 *
 *}
<div class="pkp_block block_keywords">
	<span class="title">{translate key="plugins.block.keywordCloud.title"}</span>
        <span class="content">
			{foreach from=$article_keywords key=keyword item=count}
				<span style="font-size: {$count}em";>{$keyword|escape}</span>
			{/foreach}
<!--
       	{foreach name=cloud from=$cloudKeywords key=keyword item=count}
		<a href="{url page="search" subject=$keyword}"><span style="font-size: {math equation="round(((x-1) / y * 100)+75)" x=$count y=$maxOccurs}%;">{$keyword}</span></a>
	{/foreach}
-->
        </span>
</div>
