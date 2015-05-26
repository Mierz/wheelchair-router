<?php
Class Controller_Rest extends Controller_Base {    
        
    private $login = 'medunitsa';
    private $password = 'k4bHdgev94n';
    private $lang;
    
    function __construct() 
    {
        /*if(!isset($_SERVER['HTTP_X_REQUESTED_WITH']) && empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo 'Access denied';
            exit;
        } */
        
        $this->lang = $this->getLangTitle();
        if($this->lang == 'uk') 
        {
            $this->lang = 'ua';
        }
    }
    
    public function index()
    {
        echo 'Access denied';
        exit;
    }
    
    public function getRoutes() 
    {        
        $startPoint = $this->explodeString(filter_input(INPUT_GET, 'start', FILTER_SANITIZE_STRING));
        $endPoint = $this->explodeString(filter_input(INPUT_GET, 'end', FILTER_SANITIZE_STRING));                   
        
        $bus = filter_input(INPUT_GET, 'bus', FILTER_VALIDATE_BOOLEAN);
        $trol = filter_input(INPUT_GET, 'trol', FILTER_VALIDATE_BOOLEAN);
        $direct = filter_input(INPUT_GET, 'direct', FILTER_VALIDATE_BOOLEAN);
        
        $transport = [];
                
        if($bus == 'true')
        {
            array_push($transport, 'bus');
        }
        
        if($trol == 'true')
        {
            array_push($transport, 'trol');
        }
        
        $count = count($transport);
        $types = null;
        for($i = 0; $i < $count; $i++)
        {            
            $types .= $transport[$i];
            
            if($i == 0 && $count == 2) {
                $types .= ',';
            }       
        }        
        
        if(empty($transport)) 
        {
            $types = 'bus';
        }
                        
        $params = array(
            'login'         => $this->login,
            'password'      => $this->password,
            'function'      => 'routes.Search',         
            'city'          => $_SESSION['city'],
            'start_lat'     => $startPoint['latitude'],
            'start_lng'     => $startPoint['longitude'],
            'stop_lat'      => $endPoint['latitude'],
            'stop_lng'      => $endPoint['longitude'],
            'transports'    => $types,
            'direct'        => $this->formatDirect($direct),
            'format'        => 'json',
            'lang'          => $this->lang
        );
        
        $response = json_decode(file_get_contents('https://api.eway.in.ua/' . '?' . urldecode(http_build_query($params))), true);
        //$response = 'https://api.eway.in.ua/' . '?' . urldecode(http_build_query($params));
        
        if(!isset($response['ways']))
        {
            echo json_encode(['status' => 'ERROR', 'response' => '']);
            exit();
        }
        
        $model = new Model_Transport();               
        
        $correctBus = $model->getTransport('bus', $_SESSION['city']);
        $correctTrolleybus = $model->getTransport('trol', $_SESSION['city']);

        $result = [];
        $foots = [];

        foreach($response['ways']['way'] as $key => $value)
        {            
            foreach($value['routes']['route'] as $item)
            {

                if($item['subtype'] == 'Автобус' || $item['subtype'] == 'Bus')
                {
                    if(in_array($item['title'], $correctBus, true))
                    {
                        foreach($value['stop'] as $stops)
                        {
                            $foots[] = [
                                'distance'  => $stops['distance'],
                                'time'      => $stops['time'],
                            ];
                        }

                        $result[] = [
                            'id'                => $item['id'],
                            'start_position'    => $item['start_position'],
                            'stop_position'     => $item['stop_position'],
                            'title'             => $item['title'],
                            'type'              => $item['type'],
                            'subtype'           => $item['subtype'],
                            'time'              => $item['time'],
                            'distance'          => $item['distance'],
                            'price'             => $item['price'],
                            'start_num'         => $item['stop'][0]['id'],
                            'stop_num'          => $item['stop'][1]['id'],
                            'foots'             => $foots,
                        ];
                    }
                }
                
                if($item['subtype'] == 'Тролейбус' || $item['subtype'] == 'Троллейбус' || $item['subtype'] == 'Trolleybus')
                {
                    if(in_array($item['title'], $correctTrolleybus, true))
                    {
                        foreach($value['stop'] as $stops)
                        {
                            $foots[] = [
                                'distance'  => $stops['distance'],
                                'time'      => $stops['time'],
                            ];
                        }
                        
                        $result[] = [
                            'id'                => $item['id'],
                            'start_position'    => $item['start_position'],
                            'stop_position'     => $item['stop_position'],
                            'title'             => $item['title'],
                            'type'              => $item['type'],
                            'subtype'           => $item['subtype'],
                            'time'              => $item['time'],
                            'distance'          => $item['distance'],
                            'price'             => $item['price'],
                            'start_num'         => $item['stop'][0]['id'],
                            'stop_num'          => $item['stop'][1]['id'],
                            'foots'             => $foots,
                        ];
                    }
                }
                
                $foots = [];
            }
        }
        
        $stops = [];
        foreach($response['ways']['stop_titles'] as $key => $value)
        {
            array_push($stops, $value);
        }
         
        header('Content-Type: application/json');
        echo json_encode(['status' => 'OK', 'response' => $result, 'stops' => $stops]);
    }

    private function formatResponse($item, $foots, $direct)
    {
        return [
            'id'                => $item['id'],
            'start_position'    => $item['start_position'],
            'stop_position'     => $item['stop_position'],
            'title'             => $item['title'],
            'type'              => $item['type'],
            'subtype'           => $item['subtype'],
            'time'              => $item['time'],
            'distance'          => $item['distance'],
            'price'             => $item['price'],
            'start_num'         => $item['stop'][0]['id'],
            'stop_num'          => $item['stop'][1]['id'],
            'foots'             => $foots,
        ];
    }

    private function formatDirect($direct)
    {
        if($direct == 'true')
        {
            return 'true';
        }
        else
        {
            return 'false';
        }
    }
    
    public function getPoints()
    {
        $params = array(
            'login'             => $this->login,
            'password'          => $this->password,
            'function'          => 'routes.GetRouteToDisplay',         
            'city'              => $_SESSION['city'],
            'id'                => filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT),
            'start_position'    => filter_input(INPUT_GET, 'start', FILTER_SANITIZE_NUMBER_INT),
            'stop_position'     => filter_input(INPUT_GET, 'stop', FILTER_SANITIZE_NUMBER_INT),   
            'lang'              => $this->lang                
        );

        $response = json_decode(file_get_contents('https://api.eway.in.ua/' . '?' . urldecode(http_build_query($params))), true);
        //$response = 'https://api.eway.in.ua/' . '?' . urldecode(http_build_query($params));
        
        header('Content-Type: application/json');            
        echo json_encode(['status' => 'OK', 'response' => $response]);
    }
    
    private function explodeString($string)
    {        
        $coords = explode(", ", $string);
        
        return [
            'latitude'  => trim($coords[0], '('),
            'longitude' => trim($coords[1], ')')
            ];                
    }    
    
    public function getLanguage()
    {
        header('Content-Type: application/json');            
        echo json_encode(['status' => 'OK', 'lang' => $this->getLangTitle()]);
    }
}