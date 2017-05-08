<?php

namespace Labs\AdminBundle\Services;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class YoutubeApi {


    private $apiKey;

    private $container;

    private $client;

    private $service;
    
    private $channelID;


    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->apiKey = $this->container->getParameter('api.youtube.key');
        $this->channelID = $this->container->getParameter('api.youtube.channel.id');
        $this->client = new \Google_Client();
        $this->client->setApplicationName('Youtube_api');
        $this->client->setDeveloperKey($this->apiKey);
        $this->service = new \Google_Service_YouTube($this->client);
    }

    /**
     * @param $num
     * @return mixed
     */
    public function getChannel($num){
       $res = $this->service->channels->listChannels('id, snippet',[
           'id' => $this->channelID,
           'maxResults' => $num
       ]);
        return $res;
    }

    /**
     * @param $num
     * @return \Google_Service_YouTube_PlaylistListResponse
     */
    public function getPlayList($num){
        $res = $this->service->playlists->listPlaylists('id, snippet,contentDetails',[
            'channelId' => $this->channelID,
            'maxResults' => $num
        ]);
        return $res;
    }

    /**
     * @param $num
     * @return array
     */
    public function getSearchVideo($num)
    {
        $videos = [];
        $res = $this->service->search->listSearch('id,snippet',[
            'channelId'  => $this->channelID,
            'maxResults' => $num,
            'order'      => 'date',
            'type'       => 'video'
        ]);
        foreach ($res['items'] as $video){
            $videos[] = [
                'Title' => $video['snippet']['title'],
                'Id'    => $video['id']['videoId'],
                'Thumb' => [
                    'default' => $video['snippet']['thumbnails']['default'],
                    'medium'  => $video['snippet']['thumbnails']['medium'],
                    'high'    => $video['snippet']['thumbnails']['high']
                ],
                'Description' => $video['snippet']['description'],
                'Published'   => $video['snippet']['publishedAt'],
                'Channel'   => $video['snippet']['channelTitle'],
                'ChannelId' => $video['snippet']['channelId'],
                'NextPageToken' => $video['nextPageToken'],
                'PrevPageToken' => $video['prevPageToken']
            ];
        }
        return $videos;
    }

    /**
     * @param $id
     * @return array
     */
    public function getVideoById($id)
    {
        $res = $this->service->videos->listVideos('id,snippet,contentDetails,player',[
            'id' => $id
        ]);
        $item = $res['items'];
        $video = [
            'Id' => $item[0]['id'],
            'Published' => $item[0]['snippet']['publishedAt'],
            'Channel' => $item[0]['snippet']['channelTitle'],
            'ChannelId' => $item[0]['snippet']['channelId'],
            'Title'     => $item[0]['snippet']['title'],
            'Thumb' => [
                'default' => $item[0]['snippet']['thumbnails']['default'],
                'medium'  => $item[0]['snippet']['thumbnails']['medium'],
                'high'    => $item[0]['snippet']['thumbnails']['high']
            ],
            'Descript'  => $item[0]['snippet']['description'],
            'Duration'  => $item[0]['contentDetails']['duration'],
            'Player'    => [
                'embedHtml'  => $item[0]['player']['embedHtml']
            ]
        ];
        return $video;
    }

    public function setUploadVideo(){}
    
}