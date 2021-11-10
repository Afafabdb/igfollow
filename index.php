<?php
include('Instagram_config.php');
include('instagram_class.php');
 
$username = 'yirimo2667';   // your username
$password = 'adarshm98';   // your password
 
$insta = new instagram();
$response = $insta->Login($username, $password);
 
 
if(strpos($response[1], "Sorry")) {
    echo "Request failed, there's a chance that this proxy/ip is blocked";
    print_r($response);
    exit();
}        
if(empty($response[1])) {
    echo "Empty response received from the server while trying to login";
    print_r($response);
    exit();
}
 
$search = $searchtag[array_rand($searchtag)];
$res = $insta->SearchTag($search);
 
$decode = json_decode($res[1], true);
$i=0;
 
foreach($decode['items'] as $data)  {
    $i++;
    $d = explode("_", $data['id']);
    $media_id = $d[0];
    $user_id = $d[1];
    $like_count = $data['like_count'];
    $comment_count = $data['comment_count'];
    $username = $data['user']['username'];
    $haslike = $data['has_liked'];
 
    if ($i<40)  {
        if ($like_count < 20 )  {
            $fakecomment = $hello[array_rand($hello)].' '.'@'. $username . '' . $praise[array_rand($praise)];
            $fres=$insta->IsFriend($user_id);
            $friend = json_decode($fres[1], true);
 
            if (!$friend['following'] && !$friend['is_private'] && !$friend['outgoing_request'])  {
                $res=$insta->PostFollow($user_id);
                echo "<br> after follow";
                if (!$haslike) {
                    echo "<br> All auto comment, post, Like";
                    sleep(rand(15,20));
                    $res=$insta->PostLike($media_id);
                    sleep(rand(15,20));
                    $res=$insta->PostComment($fakecomment, $media_id);  
                    sleep(rand(15,20)); 
                }              
            } else {
                    if (!$haslike) {
                    echo "<br> Like only";
                    sleep(rand(15,20));
                    $res=$insta->PostLike($media_id);   
                    }
            }
        }
    }
    unset($res);   
}
 
exit();
?>
