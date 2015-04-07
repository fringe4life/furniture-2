validEmail = (email) ->
  regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/
  return regex.test(email)

validSubject = (subject) ->
  sub = true
  sub =  false if subject is "" or null or subject.length < 3 or subject.length > 40
  return sub

validMsg = (message) ->
  msg = true
  msg = false if message is "" or null or message.length < 20 or message.length > 1000
  return msg

validCsrf = (csrf) ->
  $has_csrf = true
  $has_csrf = false if csrf is "" and csrf is null
  return $has_csrf

validateInputs = (values) ->
  $email = validEmail(values[0])
  $sub = validSubject(values[1])
  $msg = validMsg(values[2])
  $csrf = validCsrf(values[3])
  $conditions = (($email and $sub and $msg and $csrf) is true) ? true : false
  return $conditions

validateInputsArray = (values) ->
  $email = validEmail(values[0])
  $sub = validSubject(values[1])
  $msg = validMsg(values[2])
  $csrf = validCsrf(values[3])
  $array = [
    $email
    $sub
    $msg
    $csrf
  ]
  return $array

removeErrorStyles = ($selectorSub, $selectorEmail, $selectorText, $selectorOutput) ->
  $backgroundColor = "#F5F5F5";
  $($selectorSub).text("")
  $($selectorSub).css("background-color",$backgroundColor)

  $($selectorEmail).text("")
  $($selectorEmail).css("background-color", $backgroundColor)

  $($selectorText).text("")
  $($selectorText).css("background-color", $backgroundColor)
  return

enhance = () ->
  #$(" > label[for]",  $(this) ).css({"font-size":"17px"}); # , "color":"red", "text-decoration": "underline"
  $("#labelContact:not(#formEmailSymbol)").css({"font-size":"17px"});
  return

removeEnhance = () ->
  #$(" > label[for]",$(this) ).css({"font-size":"15px"}); #, "color":"black" , "text-decoration": "none"
  $("#labelContact").css({"font-size":"15px" , "text-decoration": "none"});
  return

getValues = (selectorInputs, $selectorCsrf) ->
  $em = $(selectorInputs[0]).val()
  $subject = $(selectorInputs[1]).val()
  $message = $(selectorInputs[2]).val()
  $csrf = $($selectorCsrf).val()
  $array = [
    $em
    $subject
    $message
    $csrf
  ]
  return $array

processErrors = (selectorsErrors, $selectorCsrf, booleanValues) ->
  $selectorSub = selectorsErrors[0];
  $selectorEmail = selectorsErrors[1];
  $selectorMsg = selectorsErrors[2];
  $selectorOutput = selectorsErrors[3];
  $sub = booleanValues[0];
  $email = booleanValues[1];
  $msg = booleanValues[2];
  $has_csrf = booleanValues[3];
  if not $sub
    $($selectorSub).text("subject was missing or not longer than 3 letters")
    $($selectorSub).css("background-color",$errorColor)
  else 
    $($selectorSub).text("")
  if not $email
    $($selectorEmail).text("email was incorrect")
    $($selectorEmail).css("background-color", $errorColor)
  else
    $($selectorEmail).text("")
  if not $msg
    $($selectorMsg).text("message was missing or not longer than 20 letters")
    $($selectorMsg).css("background-color", $errorColor)
  else
    $($selectorMsg).text("")
  if not $has_csrf
    $($selectorOutput).text("A Strange error occured, try refreshing the page, server error")

processContactForm = (e,$formID, $errorColor) ->
  e.preventDefault()  #Prevent the default action, which in this case is the form submission.

  $selectorCsrf = $formID + " > input[name=csrf_token]";
  $selectorSub = $formID + " > #subjectError";
  $selectorEmail = $formID + " > #emailError";
  $selectorMsg = $formID + " > #textError";
  $selectorOutput = $formID + " > #outcomeEmailForm";
  selectorsErrors = [
    $selectorSub
    $selectorEmail
    $selectorMsg
  ]

  selectorsInputs = [
    "input#e"
    "input#subject"
    "input#msg"
  ]

  values = getValues(selectorInputs, $selectorCsrf)
  console.log "#{values[0]} #{values[1]} #{values[2]} #{values[3]}"

  meetsFormConditions = validateInputs(values)

  if not meetsFormConditions
    individualValues = getValues(selectorErrors, $selectorCsrf ,values)
    processErrors(individualValues)
  else
    removeErrorStyles($selectorSub, $selectorEmail, $selectorMsg, $selectorOutput)
    #Perform an asynchronous HTTP (Ajax) request.
    $.ajax(
      url : 'ajax/email.php'
      type : 'POST'
      data : {
        'e' : values[0]
        'subject' : values[1]
        'message' : values[2]
        'csrf_token' : values[3]
      }
      'beforeSend': ->
        $($selectorOutput).text("Sending...")
      'success': ->
        #You would generally make changes to the DOM at this stage, maybe
        #adding a success message to the form.
        $output = "Sent"
        $($selectorOutput).text($output)
        $($selectorOutput).css("height", "20px")
    ).fail ->
      $($formID).css("background-color", $errorColor)
      $($selectorOutput).text("Sending failed...")
      #alert 'Sorry, there seems to be a problem contacting the server.'
      return
    return false
  return


rotateImages = () ->
  currentPhoto = $('#imageGallery div.current');
  
  nextPhoto = currentPhoto.next();
  # loops back to the first
  nextPhoto = $('#imageGallery div:first') if nextPhoto.length is 0
  
  currentPhoto.removeClass("current").addClass("previous");
  currentPhoto.css("opacity", "0.5");
  nextPhoto.css("opacity", "0.0");
  nextPhoto.addClass("current");
  nextPhoto.animate({ opacity: 1.0 }, 1000, updateTense(currentPhoto));
  updateTense = (currentPhoto) ->
    currentPhoto.removeClass("previous");
	

$(document).ready ->
  setInterval("rotateImages()", 3000);
  $formID = 'form#emailForm'
  $errorColor = "rgba(255,0,0,0.3)";

  $(document).on("mouseover", $formID, enhance)
  $(document).on("mouseleave", $formID, removeEnhance)

  #Adding submit listener to our form, again with anonymous function as handler.
  $($formID).submit (e) ->
    processContactForm e,$formID, $errorColor