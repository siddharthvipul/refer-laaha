<?php

namespace Drupal\erpw_in_app_notification;

/**
 * Interface Helper Service Interface.
 */
interface HelperServiceInterface {

  /**
   * Get notifiation event by event ID.
   *
   * @param string $machine_name
   *   The machine_name of the notification event.
   *
   * @return array
   *   The information of notification event.
   */
  public function getEventDetailsByEventId($machine_name);

  /**
   * Get unprocessed notifiation ids.
   *
   * @return array
   *   The information of notification list from notification entities id.
   */
  public function getNotificationIds();

}
