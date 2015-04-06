   
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
               
removeErrorStyles = ($selectorSub, $selectorEmail, $selectorText, $selectorOutput) ->
    $backgroundColor = "#F5F5F5";
    $($selectorSub).text("")
    $($selectorSub).css("background-color",$backgroundColor)
    
    $($selectorEmail).text("")
    $($selectorEmail).css("background-color", $backgroundColor)
    
    $($selectorText).text("")
    $($selectorText).css("background-color", $backgroundColor)


enhance = () ->
  #$(" > label[for]",  $(this) ).css({"font-size":"17px"}); # , "color":"red", "text-decoration": "underline"
  $("#labelContact:not(#formEmailSymbol)").css({"font-size":"17px"});

removeEnhance = () ->
  #$(" > label[for]",$(this) ).css({"font-size":"15px"}); #, "color":"black" , "text-decoration": "none"
  $("#labelContact").css({"font-size":"15px" , "text-decoration": "none"});
       
$(document).ready ->
  
  
  
  
  
  
  
  
  
  $formID = 'form#emailForm'
  $errorColor = "rgba(255,0,0,0.3)";
  
  $(document).on("mouseover", $formID, enhance)
  $(document).on("mouseleave", $formID, removeEnhance)
  
  #Adding submit listener to our form, again with anonymous function as handler.
  $($formID).submit (e) ->
    #Prevent the default action, which in this case is the form submission.
    e.preventDefault()
    
    $em = $("input#e").val()
    $subject = $("input#subject").val()
    $message = $("input#msg").val()
    console.log "#{$em} #{$subject} #{$message}"
    
    $csrf_selector = "input[name=csrf_token]"
    
    $csrf = $($csrf_selector).val()
    $has_csrf = true
    $has_csrf = false if $csrf is "" and $csrf is null
    
    console.log "#{$csrf} security token"
    $sub = validSubject($subject)
    $msg = validMsg($message)
    $email = validEmail($em)
    meetsFormConditions = false
    meetsFormConditions = true if ($sub and $msg and $email and $has_csrf)
    $selectorSub = $formID + " > #subjectError";
    $selectorEmail = $formID + " > #emailError";
    $selectorMsg = $formID + " > #textError";
    $selectorOutput = $formID + " > #outcomeEmailForm";
    if not meetsFormConditions
      if not $sub
        $($selectorSub).text("subject was missing or not longer than 3 letters")
        $($selectorSub).css("background-color",$errorColor)
      if not $email
        $($selectorEmail).text("email was incorrect")
        $($selectorEmail).css("background-color", $errorColor)
      if not $msg
        $($selectorMsg).text("message was missing or not longer than 20 letters")
        $($selectorMsg).css("background-color", $errorColor)
      if not $has_csrf
        $($selectorOutput).text("A Strange error occured, try refreshing the page")
    else
        removeErrorStyles($selectorSub, $selectorEmail, $selectorMsg, $selectorOutput)
        #Perform an asynchronous HTTP (Ajax) request.
        $.ajax(
          url : 'ajax/email.php'
          type : 'POST'
          data : {
            'e' : $em
            'subject' : $subject
            'message' : $message
            'csrf_token' : $csrf
          }
          'beforeSend': ->
            $($selectorOutput).text("Sending...")
          'success': ->
            #You would generally make changes to the DOM at this stage, maybe
            #adding a success message to the form.
            $output = "Sent"
            $($selectorOutput).text($output)
            $($selectorOutput).css("height", "20px")
         ).fail -> #The callback function which is called is the request fails.
          #You would generally make changes to the DOM at this stage, maybe
          #adding a failure message to the form.
          $($formID).css("background-color", $errorColor)
          $($selectorOutput).text("Sending failed...")
          #alert 'Sorry, there seems to be a problem contacting the server.'
          return
        return false
      return
    