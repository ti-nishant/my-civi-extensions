<?php

require_once 'CRM/Core/Form.php';

class CRM_Caseui_Form_Caseui extends CRM_Core_Form {

  public function buildQuickForm() {
    $this->add('text', 'case_type_name', 'Case Type Name', '',TRUE);
  //  $this->addRule('activity-types', ts('%1 is a required field.', array(1 => 'Activity Types')), 'required');
    $buttons = array(
      array(
        'type' => 'submit',
        'name' => 'submit',
        'value' => 'Submit',
      ),
    );

    require_once 'api/api.php';
    $params = array(
      'option_group_id' => 2,
      'version' => 3,
      'component_id' => 7,
    );

    $result = civicrm_api('option_value', 'get', $params);
    foreach ($result['values'] as $option) {
      $options[$option['label']] = $option['label'];
    }

    $timelineOpts = array(
      'true' => "True",
      'false' => "False",
    );

    $statusOptions = CRM_Core_PseudoConstant::activityStatus();

    $refSelectOpts = array(
      'newest' => "Newest",
      'oldest' => "Oldest",
    );

    $relationshipTypes = CRM_Core_PseudoConstant::relationshipType();
    //print_r($relationshipTypes);
    //die;

    $this->assign('case_type_name');
    $this->assign('options', $options);
    $this->assign('relationshipTypes', $relationshipTypes);
    $this->assign('refSelectOpts', $refSelectOpts);
    $this->assign('statusOptions', $statusOptions);
    $this->assign('timelineOpts', $timelineOpts);
    $this->addButtons($buttons);
  }

  public function setDefaultValues() {
    
  }

  public function postProcess() {
    //find the custom template dir path
    $config = CRM_Core_Config::singleton();
    $templateDir = $config->customTemplateDir;

    //filename for the case type
    $fileName = str_replace(' ', '', $_POST['case_type_name']) . ".xml";

    //XMLWriter config
    $writer = new XMLWriter();
    $writer->openUri($templateDir . "/CRM/Case/xml/configuration/" . $fileName);
    $writer->setIndent(true);
    $writer->setIndentString(" ");

    //Start creating XML data
    $writer->startDocument("1.0", "iso-8859-1"); // xml type
    //outermost tag
    $writer->startElement('CaseType');

    $writer->startElement('name');
    $writer->text($_POST['case_type_name']);
    $writer->endElement();

    // Start adding Activity Types
    $writer->startElement("ActivityTypes");

    foreach ($_POST['activity-types'] as $activityType) {
      if (isset($activityType['value'])) {
        $writer->startElement('ActivityType');
        $writer->startElement('name');
        $writer->text($activityType['value']);
        $writer->endElement();

        if (!empty($activityType['instance'])) {
          $writer->startElement('max_instances');
          $writer->text($activityType['instance']);
          $writer->endElement();
        }
        $writer->endElement();
      }
    }

    $writer->endElement();
  //endTag for activity types
    
  //Start adding Acitivity Sets
    $writer->startElement('ActivitySets');
    foreach ($_POST['activity-sets'] as $activitySet) {
      $writer->startElement('ActivitySet');
      $writer->startElement('name');
      $writer->text($activitySet['activitySetName']);
      $writer->endElement();
      $writer->startElement('label');
      $writer->text($activitySet['activitySetLabel']);
      $writer->endElement();
      if (isset($activitySet['timeLineBool'])) {
        $writer->startElement('timeline');
        $writer->text($activitySet['timeLineBool']);
        $writer->endElement();
      }

      foreach ($activitySet['set'] as $set) {
        if (isset($set['value'])) {
          $writer->startElement('ActivityType');
          $writer->startElement('name');
          $writer->text($set['value']);
          $writer->endElement();
          $writer->startElement('reference_activity');
          $writer->text($activitySet['globalRefActivity']);
          $writer->endElement();
          $writer->startElement('reference_offset');
          $writer->text($set['offset']);
          $writer->endElement();
          $writer->startElement('reference_select');
          $writer->text($set['refSelect']);
          $writer->endElement();
          $writer->endElement();
        }
      }
      $writer->endElement();
    }
    $writer->endElement(); 
//end Tag for activity sets

//Start Adding Case Roles/Relationships
    $writer->startElement('CaseRoles');
    foreach ($_POST['relationship-type'] as $relationshipType) {
      $writer->startElement('RelationshipType');
      if (isset($relationshipType['value'])) {
        $writer->startElement('name');
        $writer->text($relationshipType['value']);
        $writer->endElement();
        if (isset($relationshipType['creator'])) {
          $writer->startElement('creator');
          $writer->text($relationshipType['creator']);
          $writer->endElement();
        }
        if (isset($relationshipType['manager'])) {
          $writer->startElement('manager');
          $writer->text($relationshipType['manager']);
          $writer->endElement();
        }
        $writer->endElement();
      }
    }
    $writer->endElement(); //end Tag for Case Roles
    $writer->endElement(); //endTag for main tag case type
    $writer->endDocument();
    header('Content-type: text/xml');
    
    //Write to file
    $writer->flush();
  }

}