<?php

/**
 * @file plugins/blocks/keywordCloud/KeywordCloudBlockPlugin.inc.php
 *
 * Copyright (c) 2013-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Changed for OJS 3 by Daniela Wolf, Heidelberg University Library
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class KeywordCloudBlockPlugin
 * @ingroup plugins_blocks_keyword_cloud
 *
 * @brief Class for keyword cloud block plugin
 */

import('lib.pkp.classes.plugins.BlockPlugin');
import('lib.pkp.classes.controlledVocab.ControlledVocabEntryDAO');
import('classes.article.ArticleDAO');

define('KEYWORD_BLOCK_MAX_ITEMS', 20);
define('KEYWORD_BLOCK_CACHE_DAYS', 2);

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
	 * @return $string
	 */


	function getContents(&$templateMgr, $request = null) {
                $journal = $request->getJournal();
                $journalId = $journal->getId();
		$article_ids = array();

		//Get all published Articles of this Journal
		$publishedArticleDao = DAORegistry::getDAO('PublishedArticleDAO');
        	$publishedArticles =& $publishedArticleDao->getPublishedArticlesByJournalId($journalId, $rangeInfo = null, $reverse = true);

		//Get all IDs of the published Articles
		while ($publishedArticle = $publishedArticles->next()) {
			$articleId = $publishedArticle->getId();
			array_push($article_ids, $articleId);
		}

		//Get all Keywords from the published articles of this jorunal
		$submissionKeywordDao = DAORegistry::getDAO('SubmissionKeywordDAO');
		$article_keywords = array();
		foreach ($article_ids as $article_id) {
				array_push($article_keywords, $submissionKeywordDao->getKeywords($article_id, array(AppLocale::getLocale())));
			}

		//Put all Keywords from many arrays in on array
		$all_keywords = array();
		foreach($article_keywords as $keywords) {
			foreach($keywords as $keyword) {
				foreach($keyword as $k) {
				array_push($all_keywords, $k);
				}
			}
		}

		//Count the keywords					
		$count_keywords = (array_count_values($all_keywords));

		//Sort the keywords frequency-based
                arsort($count_keywords, SORT_NUMERIC);

		//Put only the 20 most often used keywords in an array
		$i=0;
                $newCount = array();
                foreach ($count_keywords as $c => $v) {
                        $newCount[$c] = $v;
                        if ($i++ >= 19) break;
                }

		//Now sort the array alphabetically
                ksort($newCount);

		//Get the frequency of the most often used keyword
		$maxOccurs = max($count_keywords);
                
		//send both to the template
		$templateMgr->assign('article_keywords', $newCount);
		$templateMgr->assign_by_ref('maxOccurs', $maxOccurs);

		return parent::getContents($templateMgr);
	}

}

?>
