<div id="tabs-1" class="border" >
    <div id="imageGallery" class="center" >
        <div class="current"><h1>Chairs</h1> <img src="img-large/sofainoak.jpg" ></div>
        <div><h1>Tables</h1> <img src="img-large/oakandjarrahlowtable.jpg"></div>
<div><h1>Chairs</h1> <img src="img-large/highbackedchairs.jpg"></div>
<div><h1>Sofa</h1> <img src="img-large/geofsofa.jpg"></div>
       
       
       
       
    </div>
    
    <div id="text" > <p id="first"><span id="welcome">Welcome</span> to my website and to a sample of some of my work.</p><div id="remainingText"> <p>As with clothing, furniture styles change with fashion - but the best quality clothing design has a longevity which transcends the fashion of the moment.</p>  <p><span class="bold">This</span> is what I aim to achieve with my furniture. <span class="s-bold">Elegant clean-lined curves</span> and forms which are visually appealing, but which are also supremely functional.</p> <p>High production values with emphasis on strength but avoiding excessive weight; high quality materials, and finishes which enhance natural colour, age gracefully, yet can be repaired.</p> </div> </div>   <div class="break"></div><p id="seriousFun">That's the serious part. Then there's the fun part.</p> <div id="woodworking"><img src="images/woodwork.jpg" alt="Having Fun With Woodwork!" /></div> <p>"Fun" is not a serious academic word, but it's the "fun" of creating something new which makes the dust, noise and hours of polishing worth it. An element of play is an important part of the design process, and an antidote to dry intellectualism. I hope an element of fun shows through in the final product and evokes an emotional response in those who encounter or live with my work.</p>  <blockquote id="comission"><p><span id= "comissionText">Work on commission:</span> I welcome enquiries, tentative or otherwise, and will reply promptly. I regularly deal with clients who are at some distance from me.</p> <p>I carry very little stock, but make items as commissioned or for exhibition. Some of my designs, for instance chairs, are produced in small runs, while other items are made singly. Yet other pieces are unique and cannot be reproduced exactly but may be recreated in essence.</p></blockquote> <p class="right" id="quoteName">- Chris Wilson</p>
<div class="break"></div>

<div id="contactForm">
<form method="post" action=""  id="emailForm">
    
    <label id="labelContact" title="chris@chriswilsondesign.co.nz">Contact <span class="hide"><span>Me:</span>  <em >chris<span class="emailSymbol" id="formEmailSymbol">@</span><span id="emailAddressForm">chriswilsondesign.co.nz</span> </em></span></label>
        <label for="e"><span class="emailSymbol">@</span></label>
        <input type="email"  id="e" placeholder="Your Email" required="required" a><span class="required">*</span><br />
    <span id="emailError"></span><br />
   <label for="subject"> Subject</label>
     <input type="text" id="subject" placeholder="Your Subject" required="required"><span class="required">*</span><br />
     <span id="subjectError"></span><br />
        <label for="msg">Message </label>
        <input type="textarea" id="msg" placeholder="Your message here..." required="required" cols="40" rows="6" wrap="soft"><span class="required">*</span><br />
     <span id="textError"></span><br />
   
    <div id="emailSubmit">
        <input type="submit" id="submit" class="center" value="Send">
    </div>
    <span class="required">*</span><span> Is a required field</span>
    <p id="outcomeEmailForm"></p>
    <?php
        echo csrf_token_tag();
    ?>
</form>
</div>



</div> 