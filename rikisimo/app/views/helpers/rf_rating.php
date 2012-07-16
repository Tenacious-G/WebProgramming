<?php
/**
 * $Id: rf_rating.php 33 2007-01-07 22:34:30Z thepaper $
 * ========================================================================
 * star rating helper - used with rf_rating component
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
class RfRatingHelper extends Helper {
    var $helpers = array('Ajax', 'Html');

    /** list of 'hints' to display for rating */
    var $_hints = array('Easy', 'Okay', 'Some difficulty', 'Use the force', 'You must be kidding');

    /** show hint or not */
    var $_showHints = false;

    /**
     * display rating bar
     *
     * @param int id
     * @param array rating info
     * @param boolean text - print text of rating (2.5/5)
     */
    function ratingBar($info, $text = false, $user = false, $top = false) {
        $width = $info['unit_width'] * $info['units'];
        $liWidth = @number_format($info['rating']/$info['votes'], 2) * $info['unit_width'];

        // text representation of rating value
        $ratingText = $info['rating_value']. '/' . $info['units'];


        //$htmlString = '<div id="ratingblock">'."\n";
		$ratingblock = 'ratingblock'.$info['id'];
		if($top) {
			$ratingblock.='top';
		}
        $htmlString = $this->Ajax->div($ratingblock);

        // display current rating
		$unit_long = 'unit_long' . $info['id'];
		if($top) {
			$unit_long.='top';
		}
		$unit_ul = 'unit_ul'.$info['id'];
		if($top) {
			$unit_ul.='top';
		}
        if($text) {
        $htmlString .= '<div id="'.$unit_long.'" style="width:125px; float:left;">'; }
        else {
        $htmlString .= '<div id="'.$unit_long.'">';        
        }
	    $htmlString .= '<ul id="' . $unit_ul.'" class="unit-rating" style="width:' . $width . 'px">';
        
        $htmlString .= '<li class="current-rating" style="width:' . $liWidth . 'px;">';
        $htmlString .= $ratingText;
        $htmlString .= '</li>';

        // draw voting stars if user has not voted yet
        if (!$info['voted']) {
            for ($i = 1; $i <= $info['units']; $i++) {

                $htmlString .= '<li>';
                $ajaxOptions = array('update' => $ratingblock, 'class' => "r$i-unit rater", 'loading'=>$ratingblock.'.style.opacity=0; return false;','complete'=>'new Effect.Opacity("'.$ratingblock.'", { from: 0, to: 1, duration: 0.5 }); return false;
                  
                  ');
               
                // add tooltip hints
                if ($this->_showHints) {
                    $hintIndex = $i - 1;
                    $ajaxOptions['title'] = $this->_hints[$hintIndex];
                }

                // add params to voting url
                $url = Router::url(array('controller'=>'nodes', 'action'=>'vote', $i, $info['id']));

                $htmlString .= $this->Ajax->link($i, $url, $ajaxOptions);

                $htmlString .= '</li>';
            }
            $i =0;
        }

        $htmlString .= '</ul>';


        $htmlString .= '</div>';

        // show text representation of rating and votes

        if(!$info['votes']) $voteString = 0;
        else $voteString = $info['votes'];        
        if ($info['votes'] == 1) {
            $voteString .= ' '.__('vote',true);
        } else {
            $voteString .= ' '.__('votes',true);
        }
		
		if(isset($info['votes']) and $info['votes']>0):
			$voteString = $this->Html->link($voteString, array('action'=>'vvotes', $info['id']));
		endif;
/*
        $htmlString .= '<div id="ratingtxt">';
        if ($text) {
            $htmlString .= '<span id="ratetext">' . $ratingText . '</span>';
        }

        $htmlString .= ' <span id="totalvotes">' .  $voteString . '</span>';
        $htmlString .= '</div>';
*/
         if($text) {
          $htmlString .= "<div class=\"general_info\" style=\"margin-left:10px;float:left;width:200px;margin-top:3px;\">"."(".$voteString;
          if(!$info['voted']) {
            $htmlString .= ', '.__('you didn\'t vote yet', true);; 
          }
          elseif($user) {
                $ajaxOptions = array('update' => $ratingblock,'loading'=>$ratingblock.'.style.opacity=0; return false;','complete'=>'new Effect.Opacity("'.$ratingblock.'", { from: 0, to: 1, duration: 0.5 }); return false;');
                $htmlString .= ', '.$this->Ajax->link(__('remove my vote',true),
 					array('controller'=>'nodes', 'action'=>'removeVote', $info['id']), $ajaxOptions);
//            $htmlString .= ', '.$this->Html->link(,);
          }
          
          $htmlString .= ")</div>";
          $htmlString .= '<div class="clear"></div>';
        }

        $htmlString .= $this->Ajax->divEnd('');

        return $this->output($htmlString);

    }//ratingBar()

}//RfRatingHelper
?>
