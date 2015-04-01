   

               
      
$(document).ready ->
  $formID = 'form#emailForm'
  IsEmail = (email) -> 
    regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/
    return regex.test(email)
  
  
  #Adding submit listener to our form, again with anonymous function as handler.
  $($formID).submit (e) ->
    
    #Prevent the default action, which in this case is the form submission.
    e.preventDefault()
    
    $em = $("input#e").val()
    $subject = $("input#subject").val()
    $message = $("input#msg").val()
    console.log "#{$em} #{$subject} #{$message}"
    $sub = true
    $sub = false if $subject is "" or null
    
    $msg = true
    $msg = false if $msg is "" or null
    
    $isEmail = IsEmail($em)
    alert $isEmail
    if ($isEmail and $sub and $msg)
        $formData = $('#emailForm').serialize()
        console.log "#{$formData} formDaata"
        #Perform an asynchronous HTTP (Ajax) request.
        $.ajax(
          url: 'ajax/email.php'
          type: 'POST'
          data: {
            'e' : $em
            'subject' : $subject
            'message' : $message
          }
          #dataType: 'json'
         ).done((data) ->
          #You would generally make changes to the DOM at this stage, maybe
          #adding a success message to the form.
          alert "#{data} data"
          #The callback function which is called is the request fails.
          return
         ).fail ->
          #You would generally make changes to the DOM at this stage, maybe
          #adding a failure message to the form.
          alert 'Sorry, there seems to be a problem contacting the server.'
          return
        return false
      return