<?php

/**
 * @file plugins/blocks/keywordCloud/KeywordCloudBlockPlugin.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Changed for OJS 3 by Daniela Wolf, Heidelberg University Library
 * Distributed under the GNU GPL v3.
 *
 * For full terms see the file LICENCE.
 *
 * @class KeywordCloudBlockPlugin
 * @ingroup plugins_blocks_keyword_cloud
 *
 * @brief Class for keyword cloud block plugin
 */

import('lib.pkp.classes.plugins.BlockPlugin');

define('KEYWORD_BLOCK_MAX_ITEMS', 20);

class KeywordCloudBlockPlugin extends BlockPlugin {
	/**
	 * Get the display name of this plugin.
	 * @return String
	 */
	function getDisplayName() {
		return __('plugins.block.keywordCloud.displayName');
	}

	/**
	 * Get a description of the plugin.
	 */
	function getDescription() {
		return __('plugins.block.keywordCloud.description');
	}

	/**
	 * Get the HTML contents for this block.
	 * @param $templateMgr object
	 * @param $request PKPRequest
	 * @return string
	 */


	function getContents($templateMgr, $request = null) {
		$journal = $request->getJournal();
		$journalId = $journal->getId();

		//Get all published Articles of this Journal
		$publishedArticleDao = DAORegistry::getDAO('PublishedArticleDAO');
        $publishedArticles =& $publishedArticleDao->getPublishedArticlesByJournalId($journalId, $rangeInfo = null, $reverse = true);

		//Get all IDs of the published Articles
        $submissionKeywordDao = DAORegistry::getDAO('SubmissionKeywordDAO');
        //Get all Keywords from all published articles of this journal
        $all_keywords = array();
		while ($publishedArticle = $publishedArticles->next()) {
            $article_keywords = $submissionKeywordDao->getKeywords($publishedArticle->getId(),
				array(AppLocale::getLocale()))[AppLocale::getLocale()];
			$all_keywords = array_merge($all_keywords, $article_keywords);
		}

		//Count the keywords					
		$count_keywords = array_count_values($all_keywords);

		//Sort the keywords frequency-based
		arsort($count_keywords, SORT_NUMERIC);

		// Put only the most often used keywords in an array
		// maximum of KEYWORD_BLOCK_MAX_ITEMS
		$top_keywords = array_slice($count_keywords, 0, KEYWORD_BLOCK_MAX_ITEMS);

		//Now sort the array alphabetically
		ksort($top_keywords, SORT_FLAG_CASE | SORT_NATURAL);

		// Get the frequency of the most often used keyword,
		// because it is sorted it is the first element
		$maxOccurs = reset($count_keywords);
                
		//send both to the template
		$templateMgr->assign('article_keywords', $top_keywords);
		$templateMgr->assign_by_ref('maxOccurs', $maxOccurs);

		return parent::getContents($templateMgr);
	}
}

?>
