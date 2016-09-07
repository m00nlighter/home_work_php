<?php

class GetPage
{
    public function get( $queryurl, $post = false , $nocache = false )
    {

        if($post)
        {
            $query=$queryurl.$post;
        }
        else
        {
            $query=$queryurl;
        }
        if($nocache)
        {
            $queryurl = implode( "&" , [ $queryurl , $nocache ] );
        }
        $curl = curl_init();
        $uagent='Mozilla/5.0 (Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30';
        curl_setopt( $curl, CURLOPT_URL, $queryurl );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $curl, CURLOPT_TIMEOUT, 20 );
        curl_setopt( $curl, CURLOPT_USERAGENT, $uagent );
        if($post)
        {
            curl_setopt( $curl, CURLOPT_POST, count( $post ) );
            curl_setopt( $curl, CURLOPT_POSTFIELDS, $post ); 
        }
        $data = curl_exec($curl);
        $info = curl_getinfo($curl);
        $error = curl_errno($curl);
        curl_close($curl);
        
        return ['data'=>$data,'info'=>$info,'error'=>$error];
        
    }
}
?>
