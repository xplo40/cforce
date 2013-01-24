<?php
   
?>

<form method="post" action="trivia_response.php" id="trivia_form">
    <fieldset>
     <label for="first_name" id="first_name">First Name:</label><input type="text" maxlength="20" class="text" value="First Name..." name="first_name" id="first_name">
     <label for="last_name" id="last_name">Last Name:</label><input type="text" maxlength="20" class="text" value="Last Name..." name="last_name" id="last_name">
     <label for="email" id="email">Email:</label><input type="text" maxlength="80" class="text" value="Email..." name="email" id="email">
     <label for="phone" id="phone">Phone Number:</label><input type="text" maxlength="80" class="text" value="Phone Number..." name="phone" id="phone">
     <label for="response" id="response">Response to Trivia Question:</label><textarea class="text" value="Your Response..." rows="5" wrap="virtual" cols="5" name="response" id="response"></textarea>
    </fieldset>
    <fieldset>
     <input type="submit" title="Submit" class="sub" value="Submit" id="submit">
    </fieldset>
   </form>


