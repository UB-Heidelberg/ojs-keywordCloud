{**
 * plugins/blocks/keywordCloud/block.tpl
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Changed for OJS 3 by Daniela Wolf, Heidelberg University Library
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Keyword cloud block plugin
 *
 *}
<div class="pkp_block block_keywords">
	<span class="title">{translate key="plugins.block.keywordCloud.title"}</span>
        <span class="content">
			{foreach from=$article_keywords key=keyword item=count}
				<span style="font-size: {math equation="round(((x-1) / y * 100)+75)" x=$count y=$maxOccurs}%;">
					<a href="{url page="search" query=$keyword}">{$keyword|escape}</a>
				</span>
			{/foreach}
        </span>
</div>
