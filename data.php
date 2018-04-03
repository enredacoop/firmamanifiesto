<?php
      $api_key = '';
      $list_id = '';

      $dc = substr($api_key,strpos($api_key,'-')+1);
      $url = 'https://'.$dc.'.api.mailchimp.com/3.0/lists/'.$list_id.'/members';
      $body = json_decode(rudr_mailchimp_curl_connect($url, 'GET', $api_key));
      foreach ($body->members as $member) {
        if ($member->merge_fields->SUPPORT!='No')  {
            $resultado[] = array(Nombre=>$member->merge_fields->FNAME);
          // echo "Nombre: " . $member->merge_fields->FNAME . " ";
          // echo "Entidad: " . $member->merge_fields->ENTITY . " ";
          // echo "<br>";
        }
      }

      echo json_encode($resultado);



      function rudr_mailchimp_curl_connect( $url, $request_type, $api_key, $data = array() ) {
        if( $request_type == 'GET' )
            $url .= '?' . http_build_query($data);

        $mch = curl_init();
        $headers = array(
            'Content-Type: application/json',
            'Authorization: Basic '.base64_encode( 'user:'. $api_key )
        );
        curl_setopt($mch, CURLOPT_URL, $url );
        curl_setopt($mch, CURLOPT_HTTPHEADER, $headers);
        //curl_setopt($mch, CURLOPT_USERAGENT, 'PHP-MCAPI/2.0');
        curl_setopt($mch, CURLOPT_RETURNTRANSFER, true); // do not echo the result, write it into variable
        curl_setopt($mch, CURLOPT_CUSTOMREQUEST, $request_type); // according to MailChimp API: POST/GET/PATCH/PUT/DELETE
        curl_setopt($mch, CURLOPT_TIMEOUT, 10);
        curl_setopt($mch, CURLOPT_SSL_VERIFYPEER, false); // certificate verification for TLS/SSL connection

        if( $request_type != 'GET' ) {
            curl_setopt($mch, CURLOPT_POST, true);
            curl_setopt($mch, CURLOPT_POSTFIELDS, json_encode($data) ); // send data in json
        }

        return curl_exec($mch);
      }

      ?>
