<?php
/*
 * @Author: Tidusvn05 
 * @Date: 2017-06-22 15:37:39 
 * @Last Modified by: Tidusvn05
 * @Last Modified time: 2017-06-22 16:40:44
 */

namespace Tidusvn05\StaticMap\Generators;

class URLGenerator implements GeneratorInterface{
  const BASE_URL = "https://maps.googleapis.com/maps/api/staticmap?";
  
  private $map;
  private $parameters = [];

  function __construct($map) {
    $this->map = $map;
  }

  public function generate(){
    $parameters_query = $this->build_paramerters();
    $marker_query = $this->build_markers_query();
    $path = $this->build_encode_path();


    $final_url =  self::BASE_URL.$parameters_query;

    if($marker_query !== '')
      $final_url .= $marker_query;

    if($path !== '')
      $final_url .= "&".$path;
    
    return $final_url;
  }

  private function init_parameters(){
    //key
    if (($key = $this->map->getKey()) !== null) {
      $this->parameters['key'] = $key;
    }

    //center
    if (($center = $this->map->getCenter()) !== null) {
      $this->parameters['center'] = $center[0] . ',' . $center[1];
    }

    //maptype
    if (($maptype = $this->map->getMaptype()) !== null) {
      $this->parameters['maptype'] = $this->maptype;
    }

    //maptype
    if (($zoom = $this->map->getZoom()) !== null) {
      $this->parameters['zoom'] = $zoom;
    }

    //size
    if (($size = $this->map->getSize()) !== null) {
      $this->parameters['size'] = $size;
    }

    //scale
    if (($scale = $this->map->getScale()) !== null) {
      $this->parameters['scale'] = $scale;
    }

    //language
    if (($language = $this->map->getLanguage()) !== null) {
      $this->parameters['language'] = $language;
    }

    //format
    if (($format = $this->map->getFormat()) !== null) {
      $this->parameters['format'] = $format;
    }

    //region
    if (($region = $this->map->getRegion()) !== null) {
      $this->parameters['region'] = $region;
    }

  }

  private function build_paramerters(){
    $this->init_parameters();
    return http_build_query($this->parameters, '', '&');
  }

  private function build_encode_path(){
    if (($path = $this->map->getPath()) !== null) {
      //$encoded_str =  Polyline::encode($path);
      $encoded_str =  "";
      $query = "path=fillcolor:". $this->map->getFillColor()."|color:". $this->map->getColor()."|enc:".$encoded_str;
      return "";
    }

    return "";
  }

  private function build_markers_query(){
    $query = "";

    if (($markers = $this->map->getMarkers()) !== null) {
      
      foreach($markers as $marker){
        $query .= "&markers=".$this->_build_marker_query($marker);
      }

      return $query;
    }

    return "";
  }

  private function _build_marker_query($marker){
    $params = []; 
    $query = "";

    if (($color = $marker->getColor()) !== null) {
      $params['color'] = $color;
    }

    if (($size = $marker->getSize()) !== null) {
      $params['size'] = $size;
    }

    if (($label = $marker->getLabel()) !== null) {
      $params['label'] = $label;
    }

    if (($icon = $marker->getIcon()) !== null) {
      $params['icon'] = $icon;
    }

    if (($anchor = $marker->getAnchor()) !== null) {
      $params['anchor'] = $anchor;
    }

    if (($anchor = $marker->getAnchor()) !== null) {
      $params['anchor'] = $anchor;
    }

    //build query
    $i = 0;
    foreach($params as $k => $val){
      $q = "$k:$val";
      $separator = "|";
      if($i === 0)
        $separator = "";
      
      $query .= $separator.$q;
      $i++;
    }

    //build locations's query
    if(count($marker->getLocations()) > 0){
      foreach($marker->getLocations() as $k => $location){
        $q = $location[0].",".$location[1];
        $separator = "|";
        if($query === "")
          $separator = "";
        $query .= $separator.$q;
      }
    }

    return $query;
  }




}

?>