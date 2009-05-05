<?php

class Awakening {
  public $username;
  public $date;
  public $time;


  function __construct($user, $params) {
    if (!empty($params['date'])) {
      $this->date = $params['date'];
    } else {
      $this->date = mktime(0,0,0,$params['date_month'],$params['date_day'],$params['date_year']);
    }
    $this->username = $user->username;
  }

  function getTemplateData() {
    return array('running-picture' => '<img src="http://www.somethingtoputhere.com/therunaround/images/runaround_feed.jpg" />',
                 'location' => $this->route,
                 'distance' => $this->miles . ' mile');
  }

  function save() {
    $ret = queryf("INSERT INTO awakening (username, date) VALUES (%s, %s, %s, %s)",
                  $this->username, $this->date, $this->miles, $this->route);
    return (bool) $ret;
  }
}
