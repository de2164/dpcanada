<?php
/*
    js constructs the "new Object()".
    Then serializes it with 
        var jq = 'jsonqry=' + JSON.stringinify(obj);
    ajax POSTs it here
    php loads POST with 
        $jq = Arg("jsonqry");
    Then unserializes it with 
        $json = json_decode($jq);
    giving a php assoc. array with the first key = "querycode",
        and other keys as required for the specific query.

    encoding:

    Payloads should be encoded to protect php, javascript, json, etc.
        php rawurlencode is equivalent to js encodeURIComponent.
    A php assoc. array is mapped to a javascript object.

    So we should be able to encode everything after serialization
        in js (var jq) and decode it before json_decode in php.
    

    $msgtype spellcheck
    accepts: language 
             text to check
    returns: same text marked up with things to check

    $msgtype goodword
    accepts: 
*/

error_reporting(E_ALL);
$relPath = "./pinc/";
require_once $relPath."dpinit.php";
require_once $relPath."DpPage.class.php";
include_once($relPath.'links.php');

$jq             = Arg("jsonqry");
//$jq             = rawurldecode($jq);
$json           = json_decode($jq);

if(! is_object($json)) {
    $err = "wc: json not-an-object error: {$jq}";
    LogMsg($err);
    send_alert($err);
    echo "not an object :: $jq";
    exit;
}


$querycode          = $json->querycode;
$username           = @$json->username;
$projectid          = @$json->projectid;
$pagename           = @$json->pagename;
$langcode           = @$json->langcode;
$text               = @$json->text;
$word               = @$json->word;
$data               = @$json->data;
$acceptwords        = @$json->acceptwords;
$mode               = @$json->mode;
$token              = @$json->token;
$flags              = @$json->flags;
if($username) {
    $User               = new DpUser($username);
}

// LogMsg("wc recv: $json");

function send_alert($msg) {
        $a          = array();
        $a["querycode"] = "popupalert";
        $a["alert"]     = _($msg);
        json_echo($a);
}

// these queries come from dp_edit.js
switch($querycode) {
    // user explicitly requests temp save
    // send back updated tags
    case "savetemp":
        $page = new DpPage($projectid, $pagename);
        $page->SaveTemp($text);
        // if($acceptwords && count($acceptwords) > 0) {
            // $words = preg_split("/\t/", $acceptwords);
            // $page->SuggestWordsArray($langcode, $words);
        // }

        $a          = array();
         $wct        = $page->WordCheckText($langcode, $text);
         list($wccount, $wcscount, $wcbcount, $pvwtext) = $wct;
        $a["querycode"] = "do" . $querycode;
        $a["token"]     = $token;
        $a["alert"]     = _("Saved.");
        // $a["wccount"]   = $wccount;
        // $a["wcscount"]  = $wcscount;
        // $a["wcbcount"]  = $wcbcount;
        // $a["pvwtext"]   = $pvwtext;
        json_echo($a);
        exit;

    case "savequit":
    case "savenext":
        if (!$User->Username()) {
	    send_alert("Couldn't save! You are no longer signed in!\n"
                       . "Please log again using another window and retry saving.");
	    exit;
        }
        $a                  = array();
	$a["querycode"]     = "do" . $querycode;
        json_echo($a);
        exit;
        
    // user hits wordcheck button for initial wordcheck,
    // or to resume suspended wordcheck 
    // Word species: 1) spellwords a. on good list b. suggested
    // c. ok here d. bad e. untouched;
    // 2) bad words a. on good list b. suggested; c. ok'ed d. virgin
    // 3) suggested words a. 
    // ?) ?say something here about suspect words?
     case "wctext":

         $page           = new DpPage($projectid, $pagename);
        // wordcheck the text and return marked-up version
         $wct            = $page->WordCheckText($langcode, $text);
         list($wccount, $wcscount, $wcbcount, $pvwtext) = $wct;
         $a              = array();
         $a["querycode"] = "wctext";
         $a["token"]     = $token;
         $a["wccount"]   = $wccount;
         $a["wcscount"]  = $wcscount;
         $a["wcbcount"]  = $wcbcount;
         $a["pvwtext"]   = $pvwtext;
         json_echo($a);
         exit;

    // user chooses a wordlist
    // case "wclist":

        // $page = new DpPage($projectid, $pagename);
        // // wordcheck the text and return marked-up version
        // $wctext = $page->WordCheckText($langcode, $text);
        // $nwc = count($wctext);

        // $a = array();
        // $a["querycode"] = $querycode;
        // $a["text"] = $wctext;
        // $a["wccount"] = $nwc;
        // json_echo($a);
        // exit;

    // user is wordchecking and hits "accept" (sending words)
    // case "wcaccept":
        // $page = new DpPage($projectid, $pagename);
        // if($acceptwords && count($acceptwords) > 0) {
            // $page->SuggestWordsArray($langcode, $acceptwords);
        // }

        // $a = array();
        // $a["querycode"] = "wcaccept";
        // $a["response"]      = "ack";
        // json_echo($a);
        // exit;

    // 

    // case "wccontext":
        // $project  = new DpProject($projectid);
        // switch($mode) {
            // default:
            // case "flagged":
                // $awords = $project->FlaggedWordCountArray($langcode);
                // $ak = array_keys($awords);
                // $av = array_values($awords);
                // array_multisort( $av, SORT_DESC, $ak, SORT_ASC, $awords);
                // break;

            // case "suggested":
                // $awords = $project->SuggestedWordCountArray($langcode);
                // $ak = array_keys($awords);
                // $av = array_values($awords);
                // array_multisort( $av, SORT_DESC, $ak, SORT_ASC, $awords);
                // break;

            // case "good":
                // $av = $project->GoodWordCountArray($langcode);
                // break;

            // case "bad":
                // $av = $project->BadWordCountArray($langcode);
                // break;

            // case "suspect":
                // $av = $project->SuspectWordCountArray($langcode);
                // break;
        // }

        // $a                  = array();
        // $a["querycode"]     = "wccontext";
        // $a["wordarray"]     = $av;
        // json_echo($a);
        // exit;

    // case "wordcontext":
        // $project  = new DpProject($projectid);
        // $wpc         = $project->WordContexts($word);

        // $a                  = array();
        // $a["querycode"]     = "wordcontext";
        // $a["projectid"]     = $projectid;
        // $a["word"]          = $word;
        // $a["contextinfo"]   = $wpc;
        // json_echo($a);
        // exit;

    // case "regexcontext":
        // $project            = new DpProject($projectid);
        // $rc                 = $project->RegexContexts($word, $flags);

        // $a                  = array();
        // $a["querycode"]     = "regexcontext";
        // $a["projectid"]     = $projectid;
        // $a["word"]          = $word;
        // $a["contextinfo"]   = $rc;
        // json_echo($a);
        // exit;

    case "setfontsize":
        $User->SetFontSize($data);
        $a                  = array();
        $a["querycode"]     = "setfontsize";
        $a["fontsize"]      = $User->FontSize();
        json_echo($a);
        exit;

    case "setfontface":
        $User->SetFontFace($data);
        $a                  = array();
        $a["querycode"]     = "setfontface";
        $a["fontface"]      = $User->FontFace();
        json_echo($a);
        exit;

    case "setzoom":
        $User->SetZoom($data);
        $a                  = array();
        $a["querycode"]     = "setzoom";
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "switchlayout":
        $User->SwitchLayout();
        $a                  = array();
        $a["querycode"]     = "switchlayout";
        $a["layout"]   = $User->IsVerticalLayout()
                                ? "vertical"
                                : "horizontal";
        $a["fontface"]      = $User->FontFace();
        $a["fontsize"]      = $User->FontSize();
        $a["zoom"]          = $User->ImageZoom();
        $a["textlines"]     = $User->TextLines();
        $a["textchars"]     = $User->TextChars();
        $a["textpct"]       = $User->TextFramePct();
        $a["imgpct"]        = 100 - $User->TextFramePct();
        json_echo($a);
        exit;

/*
    case "addgoodword":
        $project            = new DpProject($projectid);
        $project->AddGoodWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "addbadword":
        $project            = new DpProject($projectid);
        $project->AddBadWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "removegoodword":
        $project            = new DpProject($projectid);
        $project->DeleteGoodWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "removesuggestedword":
        $project            = new DpProject($projectid);
        $project->DeleteSuggestedWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "removebadword":
        $project            = new DpProject($projectid);
        $project->DeleteBadWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "goodtobadword":
        $project            = new DpProject($projectid);
        $project->DeleteGoodWord($langcode, $word);
        $project->AddBadWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "badtogoodword":
        $project            = new DpProject($projectid);
        $project->DeleteBadWord($langcode, $word);
        $project->AddGoodWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "suggestedtobadword":
        $project            = new DpProject($projectid);
        $project->DeleteSuggestedWord($langcode, $word);
        $project->AddBadWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "suggestedtogoodword":
        $project            = new DpProject($projectid);
        $project->DeleteSuggestedWord($langcode, $word);
        $project->AddGoodWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;

    case "suspecttobadword":
        $project            = new DpProject($projectid);
        $project->DeleteSuspectWord($langcode, $word);
        $project->AddBadWord($langcode, $word);
        $a                  = array();
        $a["querycode"]     = $querycode;
        $a["response"]      = "ack";
        json_echo($a);
        exit;
*/
}

function json_echo($rsp) {
    $rsp = json_encode($rsp);
//    $rsp = rawurlencode($rsp);
    echo $rsp;
}
?>
