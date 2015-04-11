(function() {
  var enhance, getValues, processContactForm, processErrors, removeEnhance, removeErrorStyles, validCsrf, validEmail, validMsg, validSubject, validateInputs, validateInputsArray;

  validEmail = function(email) {
    var regex;
    regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
    return regex.test(email);
  };

  validSubject = function(subject) {
    var sub;
    sub = true;
    if (subject === "" || null || subject.length < 3 || subject.length > 40) {
      sub = false;
    }
    return sub;
  };

  validMsg = function(message) {
    var msg;
    msg = true;
    if (message === "" || null || message.length < 20 || message.length > 1000) {
      msg = false;
    }
    return msg;
  };

  validCsrf = function(csrf) {
    var $has_csrf;
    $has_csrf = true;
    if (csrf === "" && csrf === null) {
      $has_csrf = false;
    }
    return $has_csrf;
  };

  validateInputs = function(values) {
    var $conditions, $csrf, $email, $msg, $sub, _ref;
    $email = validEmail(values[0]);
    $sub = validSubject(values[1]);
    $msg = validMsg(values[2]);
    $csrf = validCsrf(values[3]);
    $conditions = (_ref = ($email && $sub && $msg && $csrf) === true) != null ? _ref : {
      "true": false
    };
    return $conditions;
  };

  validateInputsArray = function(values) {
    var $array, $csrf, $email, $msg, $sub;
    $email = validEmail(values[0]);
    $sub = validSubject(values[1]);
    $msg = validMsg(values[2]);
    $csrf = validCsrf(values[3]);
    $array = [$email, $sub, $msg, $csrf];
    return $array;
  };

  removeErrorStyles = function($selectorSub, $selectorEmail, $selectorText, $selectorOutput) {
    var $backgroundColor;
    $backgroundColor = "#F5F5F5";
    $($selectorSub).text("");
    $($selectorSub).css("background-color", $backgroundColor);
    $($selectorEmail).text("");
    $($selectorEmail).css("background-color", $backgroundColor);
    $($selectorText).text("");
    $($selectorText).css("background-color", $backgroundColor);
  };

  enhance = function() {
    $("#labelContact:not(#formEmailSymbol)").css({
      "font-size": "17px"
    });
  };

  removeEnhance = function() {
    $("#labelContact").css({
      "font-size": "15px",
      "text-decoration": "none"
    });
  };

  getValues = function(selectorInputs, $selectorCsrf) {
    var $array, $csrf, $em, $message, $subject;
    $em = $(selectorInputs[0]).val();
    $subject = $(selectorInputs[1]).val();
    $message = $(selectorInputs[2]).val();
    $csrf = $($selectorCsrf).val();
    $array = [$em, $subject, $message, $csrf];
    return $array;
  };

  processErrors = function(selectorsErrors, $selectorCsrf, booleanValues) {
    var $email, $has_csrf, $msg, $selectorEmail, $selectorMsg, $selectorOutput, $selectorSub, $sub;
    $selectorSub = selectorsErrors[0];
    $selectorEmail = selectorsErrors[1];
    $selectorMsg = selectorsErrors[2];
    $selectorOutput = selectorsErrors[3];
    $sub = booleanValues[0];
    $email = booleanValues[1];
    $msg = booleanValues[2];
    $has_csrf = booleanValues[3];
    if (!$sub) {
      $($selectorSub).text("subject was missing or not longer than 3 letters");
      $($selectorSub).css("background-color", $errorColor);
    } else {
      $($selectorSub).text("");
    }
    if (!$email) {
      $($selectorEmail).text("email was incorrect");
      $($selectorEmail).css("background-color", $errorColor);
    } else {
      $($selectorEmail).text("");
    }
    if (!$msg) {
      $($selectorMsg).text("message was missing or not longer than 20 letters");
      $($selectorMsg).css("background-color", $errorColor);
    } else {
      $($selectorMsg).text("");
    }
    if (!$has_csrf) {
      return $($selectorOutput).text("A Strange error occured, try refreshing the page, server error");
    }
  };

  processContactForm = function(e, $formID, $errorColor) {
    var $selectorCsrf, $selectorEmail, $selectorMsg, $selectorName, $selectorOutput, $selectorSub, individualValues, meetsFormConditions, selectorInputs, selectorsErrors, values;
    e.preventDefault();
    $selectorCsrf = $formID + " > input[name=csrf_token]";
    $selectorSub = $formID + " > #subjectError";
    $selectorEmail = $formID + " > #emailError";
    $selectorMsg = $formID + " > #textError";
    $selectorOutput = $formID + " > #outcomeEmailForm";
    $selectorName = $formID + " > #nameError";
    selectorsErrors = [$selectorSub, $selectorEmail, $selectorMsg];
    selectorInputs = ["input#name", "input#e", "input#subject", "textarea#msg"];
    values = getValues(selectorInputs, $selectorCsrf);
    console.log("" + values[0] + " " + values[1] + " " + values[2] + " " + values[3]);
    meetsFormConditions = validateInputs(values);
    if (!meetsFormConditions) {
      individualValues = getValues(selectorErrors, $selectorCsrf, values);
      processErrors(individualValues);
    } else {
      removeErrorStyles($selectorSub, $selectorEmail, $selectorMsg, $selectorOutput);
      $.ajax({
        url: 'ajax/email.php',
        type: 'POST',
        data: {
          'name': values[0],
          'e': values[1],
          'subject': values[2],
          'message': values[3],
          'csrf_token': values[4]
        },
        'beforeSend': function() {
          return $($selectorOutput).text("Sending...");
        },
        'success': function(data) {
          var $output;
          $output = "Sent";
          $($selectorOutput).text($output + " " + data);
          return $($selectorOutput).css("height", "20px");
        }
      }).fail(function() {
        $($formID).css("background-color", $errorColor);
        $($selectorOutput).text("Sending failed...");
      });
      return false;
    }
  };

  $(document).ready(function() {
    var $errorColor, $formID;
    setInterval("rotateImages()", 3000);
    $formID = 'form#emailForm';
    $errorColor = "rgba(255,0,0,0.3)";
    $(document).on("mouseover", $formID, enhance);
    $(document).on("mouseleave", $formID, removeEnhance);
    return $($formID).submit(function(e) {
      return processContactForm(e, $formID, $errorColor);
    });
  });

}).call(this);
//# sourceMappingURL=javascript.js.map