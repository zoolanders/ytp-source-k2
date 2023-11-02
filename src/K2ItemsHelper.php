<?php

namespace YOOtheme\Source\K2;

// use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
// use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Registry\Registry;

require_once(JPATH_SITE.'/components/com_k2/helpers/route.php');
require_once(JPATH_SITE.'/components/com_k2/helpers/utilities.php');

class K2ItemsHelper
{
    public static function getItems(array $params)
    {
        $params = new Registry($params);

        $app = Factory::getApplication();
        $db = Factory::getDbo();

        $jnow = Factory::getDate();
        $now = (K2_JVERSION != '15') ? $jnow->toSql() : $jnow->toMySQL();
        $nullDate = $db->getNullDate();

        $cid = $params->get('category_id', null);
        $ordering = $params->get('itemsOrdering', '');
        $limit = $params->get('itemCount', 5);
        $limitstart = $params->get('limitstart', 0);

        // Get ACL
        $user = Factory::getUser();
        if (K2_JVERSION != '15') {
            $userLevels = array_unique($user->getAuthorisedViewLevels());
            $aclCheck = 'IN('.implode(',', $userLevels).')';
        } else {
            $aid = $user->get('aid');
            $aclCheck = '<= '.$user->get('aid');
        }

        // Get language on Joomla 2.5+
        $languageFilter = '';
        if (K2_JVERSION != '15' && method_exists($app, 'getLanguageFilter')) {
            if ($app->getLanguageFilter()) {
                $languageTag = Factory::getLanguage()->getTag();
                $languageFilter = $db->Quote($languageTag).", ".$db->Quote('*');
            }
        }

        // Sources (prepare the DB query)
        if ($params->get('source') == 'specific') {
            $value = $params->get('items');
            $current = array();
            if (is_string($value) && !empty($value)) {
                $current[] = $value;
            }
            if (is_array($value)) {
                $current = $value;
            }

            $items = array();

            foreach ($current as $id) {
                $query = "SELECT i.*, c.name AS categoryname, c.id AS categoryid, c.alias AS categoryalias, c.params AS categoryparams
                    FROM #__k2_items AS i
                    LEFT JOIN #__k2_categories AS c ON c.id = i.catid
                    WHERE i.published = 1
                        AND i.access {$aclCheck}
                        AND i.trash = 0
                        AND c.published = 1
                        AND c.access {$aclCheck}
                        AND c.trash = 0
                        AND (i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now).")
                        AND (i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now).")
                        AND i.id={$id}";

                if ($languageFilter) {
                    $query .= " AND i.language IN ({$languageFilter}) AND c.language IN ({$languageFilter})";
                }

                $db->setQuery($query);
                $item = $db->loadObject();

                if ($item) {
                    $items[] = $item;
                }
            }
        } else {
            $query = "SELECT i.*,";

            if ($ordering == 'modified') {
                $query .= " CASE WHEN i.modified = 0 THEN i.created ELSE i.modified END AS lastChanged,";
            }

            $query .= " c.name AS categoryname, c.id AS categoryid, c.alias AS categoryalias, c.params AS categoryparams";

            if ($ordering == 'best') {
                $query .= ", (r.rating_sum/r.rating_count) AS rating";
            }

            if ($ordering == 'comments') {
                $query .= ", COUNT(comments.id) AS numOfComments";
            }

            $query .= " FROM #__k2_items AS i RIGHT JOIN #__k2_categories AS c ON c.id = i.catid";

            if ($ordering == 'best') {
                $query .= " LEFT JOIN #__k2_rating AS r ON r.itemID = i.id";
            }

            if ($ordering == 'comments') {
                $query .= " LEFT JOIN #__k2_comments AS comments ON comments.itemID = i.id";
            }

            $tagsFilter = $params->get('tags');
            if ($tagsFilter && is_array($tagsFilter) && count($tagsFilter)) {
                $query .= " INNER JOIN #__k2_tags_xref tags_xref ON tags_xref.itemID = i.id";
            }

            $query .= " WHERE i.published = 1
                AND i.access {$aclCheck}
                AND i.trash = 0
                AND c.published = 1
                AND c.access {$aclCheck}
                AND c.trash = 0
                AND (i.publish_up = ".$db->Quote($nullDate)." OR i.publish_up <= ".$db->Quote($now).")
                AND (i.publish_down = ".$db->Quote($nullDate)." OR i.publish_down >= ".$db->Quote($now).")";

            if ($params->get('catfilter') && !is_null($cid)) {
                if ($params->get('getChildren')) {
                    $itemListModel = \K2Model::getInstance('Itemlist', 'K2Model');
                    $categories = $itemListModel->getCategoryTree($cid);
                    sort($categories);
                    $sql = @implode(',', $categories);
                    $query .= " AND i.catid IN ({$sql})";
                } else {
                    if (is_array($cid)) {
                        sort($cid);
                        $query .= " AND i.catid IN(".implode(',', $cid).")";
                    } else {
                        $query .= " AND i.catid = ".(int) $cid;
                    }
                }
            }

            $tagsFilter = $params->get('tags');
            if ($tagsFilter && is_array($tagsFilter) && count($tagsFilter)) {
                $query .= " AND tags_xref.tagID IN(".implode(',', $tagsFilter).")";
            }

            $usersFilter = $params->get('users');
            if ($usersFilter && is_array($usersFilter) && count($usersFilter)) {
                $query .= " AND i.created_by IN(".implode(',', $usersFilter).") AND i.created_by_alias = ''";
            }

            if ($params->get('FeaturedItems') == '0') {
                $query .= " AND i.featured != 1";
            }

            if ($params->get('FeaturedItems') == '2') {
                $query .= " AND i.featured = 1";
            }

            if ($params->get('videosOnly')) {
                $query .= " AND (i.video IS NOT NULL AND i.video!='')";
            }

            if ($languageFilter) {
                $query .= " AND i.language IN ({$languageFilter}) AND c.language IN ({$languageFilter})";
            }

            if ($ordering == 'comments') {
                $query .= " AND comments.published = 1";
            }

            switch ($ordering) {

                case 'date':
                    $orderby = 'i.created ASC';
                    break;

                case 'rdate':
                    $orderby = 'i.created DESC';
                    break;

                case 'alpha':
                    $orderby = 'i.title';
                    break;

                case 'ralpha':
                    $orderby = 'i.title DESC';
                    break;

                case 'order':
                    if ($params->get('FeaturedItems') == '2') {
                        $orderby = 'i.featured_ordering';
                    } else {
                        $orderby = 'i.ordering';
                    }
                    break;

                case 'rorder':
                    if ($params->get('FeaturedItems') == '2') {
                        $orderby = 'i.featured_ordering DESC';
                    } else {
                        $orderby = 'i.ordering DESC';
                    }
                    break;

                case 'hits':
                    if ($params->get('popularityRange')) {
                        if ($params->get('popularityRange') == 'today') {
                            $date = (K2_JVERSION != '15') ? $jnow->format('%Y-%m-%d').' 00:00:00' : $jnow->toFormat('%Y-%m-%d').' 00:00:00';
                            $query .= " AND i.publish_up > '{$date}'";
                        } else {
                            $query .= " AND i.created > DATE_SUB('{$now}', INTERVAL ".$params->get('popularityRange')." DAY)";
                        }
                    }
                    $orderby = 'i.hits DESC';
                    break;

                case 'rand':
                    $orderby = 'RAND()';
                    break;

                case 'best':
                    $orderby = 'rating DESC';
                    break;

                case 'comments':
                    if ($params->get('popularityRange')) {
                        $query .= " AND i.created > DATE_SUB('{$now}', INTERVAL ".$params->get('popularityRange')." DAY)";
                    }
                    $orderby = 'numOfComments DESC';
                    break;

                case 'modified':
                    $orderby = 'lastChanged DESC';
                    break;

                case 'publishUp':
                    $orderby = 'i.publish_up DESC';
                    break;

                default:
                    $orderby = 'i.id DESC';
                    break;
            }

            if ($tagsFilter && is_array($tagsFilter) && count($tagsFilter)) {
                $query .= ' GROUP BY i.id';
            }

            $query .= ' ORDER BY '.$orderby;

            $db->setQuery($query, $limitstart, $limit);
            $items = $db->loadObjectList();
        }

        // Render the query results
        $model = \K2Model::getInstance('Item', 'K2Model');

        // Import plugins
        // if ($params->get('JPlugins', 1)) {
        //     PluginHelper::importPlugin('content');
        // }

        // if ($params->get('K2Plugins', 1)) {
        //     PluginHelper::importPlugin('k2');
        // }

        // $dispatcher = \JDispatcher::getInstance();

        foreach ($items as &$item) {
            $item->text = '';

            // // Plugins
            // $params->set('parsedInModule', 1); // for plugins to know when they are parsed inside this module

            // $item->event = new \stdClass;

            // $item->event->BeforeDisplay = '';
            // $item->event->AfterDisplay = '';
            // $item->event->AfterDisplayTitle = '';
            // $item->event->BeforeDisplayContent = '';
            // $item->event->AfterDisplayContent = '';

            // // Joomla Plugins
            // if ($params->get('JPlugins', 1)) {
            //     if (K2_JVERSION != '15') {
            //         $item->event->BeforeDisplay = '';
            //         $item->event->AfterDisplay = '';

            //         $results = $dispatcher->trigger('onContentAfterTitle', array('mod_k2_content', &$item, &$params, $limitstart));
            //         $item->event->AfterDisplayTitle = trim(implode("\n", $results));

            //         $results = $dispatcher->trigger('onContentBeforeDisplay', array('mod_k2_content', &$item, &$params, $limitstart));
            //         $item->event->BeforeDisplayContent = trim(implode("\n", $results));

            //         $results = $dispatcher->trigger('onContentAfterDisplay', array('mod_k2_content', &$item, &$params, $limitstart));
            //         $item->event->AfterDisplayContent = trim(implode("\n", $results));

            //         $dispatcher->trigger('onContentPrepare', array('mod_k2_content', &$item, &$params, $limitstart));
            //     } else {
            //         $results = $dispatcher->trigger('onBeforeDisplay', array(&$item, &$params, $limitstart));
            //         $item->event->BeforeDisplay = trim(implode("\n", $results));

            //         $results = $dispatcher->trigger('onAfterDisplay', array(&$item, &$params, $limitstart));
            //         $item->event->AfterDisplay = trim(implode("\n", $results));

            //         $results = $dispatcher->trigger('onAfterDisplayTitle', array(&$item, &$params, $limitstart));
            //         $item->event->AfterDisplayTitle = trim(implode("\n", $results));

            //         $results = $dispatcher->trigger('onBeforeDisplayContent', array(&$item, &$params, $limitstart));
            //         $item->event->BeforeDisplayContent = trim(implode("\n", $results));

            //         $results = $dispatcher->trigger('onAfterDisplayContent', array(&$item, &$params, $limitstart));
            //         $item->event->AfterDisplayContent = trim(implode("\n", $results));

            //         $dispatcher->trigger('onPrepareContent', array(&$item, &$params, $limitstart));
            //     }
            // }

            // // Initialize K2 plugin events
            // $item->event->K2BeforeDisplay = '';
            // $item->event->K2AfterDisplay = '';
            // $item->event->K2AfterDisplayTitle = '';
            // $item->event->K2BeforeDisplayContent = '';
            // $item->event->K2AfterDisplayContent = '';
            // $item->event->K2CommentsCounter = '';

            // // K2 Plugins
            // if ($params->get('K2Plugins', 1)) {
            //     $results = $dispatcher->trigger('onK2BeforeDisplay', array(&$item, &$params, $limitstart));
            //     $item->event->K2BeforeDisplay = trim(implode("\n", $results));

            //     $results = $dispatcher->trigger('onK2AfterDisplay', array(&$item, &$params, $limitstart));
            //     $item->event->K2AfterDisplay = trim(implode("\n", $results));

            //     $results = $dispatcher->trigger('onK2AfterDisplayTitle', array(&$item, &$params, $limitstart));
            //     $item->event->K2AfterDisplayTitle = trim(implode("\n", $results));

            //     $results = $dispatcher->trigger('onK2BeforeDisplayContent', array(&$item, &$params, $limitstart));
            //     $item->event->K2BeforeDisplayContent = trim(implode("\n", $results));

            //     $results = $dispatcher->trigger('onK2AfterDisplayContent', array(&$item, &$params, $limitstart));
            //     $item->event->K2AfterDisplayContent = trim(implode("\n", $results));

            //     $dispatcher->trigger('onK2PrepareContent', array(&$item, &$params, $limitstart));

            //     if ($params->get('itemCommentsCounter')) {
            //         $results = $dispatcher->trigger('onK2CommentsCounter', array(&$item, &$params, $limitstart));
            //         $item->event->K2CommentsCounter = trim(implode("\n", $results));
            //     }
            // }

            // Restore the intotext variable after plugins are executed
            // $item->introtext = $item->text;

            // Remove the plugin tags
            // $item->introtext = preg_replace("#{(.*?)}(.*?){/(.*?)}#s", '', $item->introtext);
        }

        return $items;
    }
}
