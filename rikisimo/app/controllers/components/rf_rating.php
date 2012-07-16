<?php
/**
 * $Id: rf_rating.php 32 2007-01-07 21:31:06Z thepaper $
 * ========================================================================
 * star rating component - used with rf_rating helper
 *
 * @author  Jack Pham (www.reversefolds.com)
 *          copyright (c) 2007
 *
 * License and Terms:
 * This software is provided AS-IS with no explicit or implied warranties 
 * or garantees whatsoever.  USE AT OWN RISK.  The original author,
 * hosting provider and anyone/everyone he knows is NOT LIABLE for any
 * damages incurred using, downloading or looking at this this 
 * software.
 *
 * Please send any bugfixes/modifications to jack@reversefolds.com
 * ========================================================================
 */
class RfRatingComponent extends Object
{

    /** database model */
    var $_dbModel = 'Node';

    /** link/intersection model */
    var $_linkModel = 'Vote';

    /** column names for intersection table */
    var $_columns = array('node_id', 'user_id', 'vote');

    /** width in pixels of each rating unit */
    var $_unitWidth = 25;

    /** total number of rating units to display */
    var $_units = 5;

    var $controller = true;


    /**
     * component startup
     */
    function startup(&$controller) {

        $this->controller=&$controller;

    }//startup()

    /**
     * get current rating
     *
     * @param int id of the thing we're rating
     *
     * @return array 
     *              id = article id
     *              units = number of rating units
     *              unit_width = pixel width of an individual unit
     *              votes = total number of votes
     *              rating = total rating
     *              rating_value = calculated rating value
     *              voted = boolean if user has voted on article
     */
    function getRatingInfo($id, $userId) {

        $ratingInfo = array();

        // add extra information to help with rating display
        $ratingInfo['id'] = $id;
        $ratingInfo['units'] = $this->_units;
        $ratingInfo['unit_width'] = $this->_unitWidth;

        // turn off query caching to make sure we get the latest info
        $this->controller->{$this->_dbModel}->cacheQueries = false;

//        $this->controller->{$this->_dbModel}->recursive = -1;
        $this->controller->{$this->_dbModel}->contain('Vote');
        
        // get rating info 
        $rating = $this->controller->{$this->_dbModel}->find(array('id'=>$id));
      
        if(empty($rating['Vote'])) {
          $ratingInfo['votes'] = 0;
          $ratingInfo['rating'] = 0;         
        }
        else {
          $ratingInfo['votes'] = $rating[$this->_linkModel][0]['Vote'][0]['votes'];
          $ratingInfo['rating'] = $rating[$this->_linkModel][0]['Vote'][0]['rating'];
        }
        // calculate current rating value
        $ratingInfo['rating_value'] = @number_format($ratingInfo['rating']/$ratingInfo['votes'], 2); 

        // add voted info for helper to decide whether allow rating
        if($userId==null) {
          $ratingInfo['voted'] = true;
        }
        else {
          $ratingInfo['voted'] = $this->userVoted($id, $userId);
        }
        return $ratingInfo;

    }//getRatingInfo()


    /**
     * checks if user has voted
     *
     * @param int article id
     * @param int user id
     *
     * @return boolean
     */
    function userVoted($articleId, $userId) {
        $conditions = $this->_columns[0] . "=" . $articleId . " AND " . $this->_columns[1] . "=" . $userId;

        if (is_array($this->controller->{$this->_linkModel}->find($conditions))) {
            return true;
        }

        return false;

    }//userVoted()

    /**
     * record vote
     * a controller method should call this to record a vote and get the 
     * new rating values which would be ferry'd to a view that is updated
     * via ajax in the main containing view
     *
     * @param int vote value
     * @param int article id
     * @param int user id
     *
     * @return array rating information or false
     */
    function vote($vote, $articleId, $userId) {
        $currentRating = $this->getRatingInfo($articleId, $userId);

        // make sure vote value is in allowed range and user has not voted
        if (($vote >= 1) &&  ($vote <= $this->_units) && 
            (!$this->userVoted($articleId, $userId))) { 

            $totalVotes = $currentRating['votes'] + 1;
            $totalValue = $currentRating['rating'] + $vote;
/*
            $voteInfo = array(
                           $this->_dbModel => array(
                                                 'id'          => $articleId,
                                                 'votes' => $totalVotes,
                                                 'rating' => $totalValue
                                              )
                        );
*/
            // update votes and ratings for article
//            if ($this->controller->{$this->_dbModel}->save($voteInfo)) {

                // mark article user voted on
                $voter = array(
                            $this->_linkModel => array(
                                                    $this->_columns[0] => $articleId,
                                                    $this->_columns[1] => $userId,
                                                    $this->_columns[2] => $vote 
                                                 )
                         );

                if ($this->controller->{$this->_linkModel}->save($voter)) {

                    // if the update was successful, return updated rating info
                    return $this->getRatingInfo($articleId, $userId);
                }
  //          }
        }

        return false;

    }//vote()


    /**
     * set unit width and the number of units
     *
     * @param int number of units
     * @param int width
     */
    function setUnits($units, $width) {

        $this->_units = $units;
        $this->_unitWidth = $width;

    }//setUnitWidth()

    /** 
     * set the column names for the intersection table
     *
     * @param array column names
     */
    function setColumns($names) {

        $this->_columns = $names;

    }//setColumns()

    /**
     * set the main and intersection model names
     *
     * @param array model names
     *              first should be the main database model
     *              second will be the link model
     */
    function setModels($names) {

        $this->_dbModel = $names[0];
        $this->_linkModel = $names[1];

    }//setModels()


}//RatingComponent
?>
