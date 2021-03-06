<?php

include_once 'includes/db-connect.php';

include_once 'includes/functions.php';



sec_session_start();



$user_id = getUserId();

?>

<!DOCTYPE HTML>

<html>

	<head>

		<title>GSU Create Survey</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<link rel="stylesheet" href="GSUSurveyView.css">
		<script src="http://code.jquery.com/jquery-1.9.1.min.js"></script>



		<script>



			var questionArray = new Array();
			var questionNum = 0; // add to the array at the end right before submittion
			var totalQuestions = 0;
			var index = 0;

			function addQuestion(){

				var numChoice = 0;

				questionNum++;
				var lineBreak = document.createElement("br");
				var div = document.createElement("div");
				div.id = "question"+ questionNum;
				div.className = "question";

				// Creates the Question

				var question = prompt("What is your question?");



				var text = document.createElement("input");

				text.type = "input";
				text.value = question;
				text.text = question;
				text.readOnly = true;
				text.size = "75";
				div.appendChild(text);


				//--------------------------------------------------------
/*
				var responseNumber = document.createElement("input");

				responseNumber.type = "hidden";
				responseNumber.value = numChoice;
				responseNumber.readOnly = true;
				div.appendChild(responseNumber);
*/
				//-------------------------------------------------------

				var addResponseBtn = document.createElement("input");
				addResponseBtn.type = "button";
				addResponseBtn.value = "Add a New Response";



				var editQuestionBtn = document.createElement("input");
				editQuestionBtn.type = "button";
				editQuestionBtn.value = "Edit Question";



				editQuestionBtn.onclick = function editQuestion(){ //----------------------------
					var newQuestion = window.prompt("Enter in the new Question")
					text.value = newQuestion;
					text.text = newQuestion;

				};



				var removeQuestionBtn = document.createElement("input");
				removeQuestionBtn.type = "button";
				removeQuestionBtn.value = "Delete Question";


				div.appendChild(addResponseBtn);
				div.appendChild(editQuestionBtn);
				div.appendChild(removeQuestionBtn);

				
				div.appendChild(lineBreak);
				var lineBreak = document.createElement("br");
				div.appendChild(lineBreak);



				// Asks the first required Response-------------------------------------------------

				var response = prompt("Enter in the Response");

				var radio = document.createElement("input");
				radio.type = "radio";
				radio.value = response;
				radio.name = "question " + questionNum + "response";
				radio.id = "question " + questionNum + "response";

				
				var label = document.createElement("Label");
				label.setAttribute("for", "question " + questionNum + "response");
				label.innerHTML = radio.value;



				var editResponseBtn = document.createElement("input");

				editResponseBtn.type = "button";
				editResponseBtn.value = "Edit";
				editResponseBtn.onclick = function editResponse(){ //-------------------------------

					var newResponse = prompt("Enter in the new Response");
					radio.value = newResponse;
					label.innerHTML = radio.value;

				};



				div.appendChild(radio);
				div.appendChild(label);
				div.appendChild(editResponseBtn);


				// Allows for more responses to be added with a button click

				addResponseBtn.onclick = function addNewResponse(){ //--------------------

					var response = prompt("Enter in a Response");


					var radio = document.createElement("input");
					radio.type = "radio";
					radio.value = response;
					radio.name = "question " + questionNum + "response";
					radio.id = "question " + questionNum + "response";

					 
					var label = document.createElement("Label");
					label.setAttribute("for", "question " + questionNum + "response");
					label.innerHTML = radio.value;



					var editResponseBtn = document.createElement("input");
					editResponseBtn.type = "button";
					editResponseBtn.value = "Edit";
					editResponseBtn.onclick = function editResponse(){ //---------------------------------

						var newResponse = prompt("Enter in a new Response");
						radio.value = newResponse;
						label.innerHTML = radio.value;

					};



					var removeResponseBtn = document.createElement("input");
					removeResponseBtn.type = "button";
					removeResponseBtn.value = "Remove";


					removeResponseBtn.onclick = function removeResponse(){ //--------------------------

					div.removeChild(radio);
					div.removeChild(label);
					div.removeChild(editResponseBtn);
					div.removeChild(removeResponseBtn);

				};


					div.appendChild(radio);
					div.appendChild(label);
					div.appendChild(editResponseBtn);
					div.appendChild(removeResponseBtn);
					var lineBreak = document.createElement("br");
					div.appendChild(lineBreak);
					var lineBreak = document.createElement("br");
					div.appendChild(lineBreak);

				
				};



				var lineBreak = document.createElement("br");
				div.appendChild(lineBreak);

				var lineBreak = document.createElement("br");
				div.appendChild(lineBreak);

				document.getElementById('altContent').appendChild(div);



				removeQuestionBtn.onclick = function removeQuestion(){ // remove question
					var r = window.confirm("Are you sure you want to delete this question and it's responses?");

					if (r == true){
						document.getElementById('altContent').removeChild(div);
					}	
				};
			}



			function submitQuestions(){

				questionArray[index] = String(totalQuestions); // we add the number of questions to the very end of the array right before submitting
				

			}

			function createArray(){

				
				var lastChoiceNum = 0;

				var inputs = document.getElementsByTagName("INPUT"); // stores how many input elements are in the page

				for(var i = 0; i < inputs.length; i++){

					if(inputs[i].type == 'text'){ // question
						questionArray[index] = inputs[i].value;
						totalQuestions++;
						lastChoiceNum = index + 1;
						questionArray[lastChoiceNum] = 0;
						index+=2;

					}else if(inputs[i].type == 'radio'){ // choice
						
						questionArray[index] = inputs[i].value;
						questionArray[lastChoiceNum] += 1;
						index++;

					}

				}

				questionArray[index] = String(totalQuestions);


			}
			function leavePage(){
				var cond = window.confirm("All work will be lost. Leave anyway?");
				if (cond == true){
					self.open("protected_page.php");

				}
			}



			$(document).ready(function(){ 

		  		$('#btn').click(function(){ // prepare button inserts the JSON string in the hidden element 

		    		$('#str').val(JSON.stringify(questionArray)); 

		  		}); 

			});

		</script>



	</head>



	<body>

		<div id = "wrapper">

			<h1>GSU Survey</h1>

			<div id ="altContent">

				<?php if (login_check($mysqli) == 1) : ?>
					<h2>Create a Survey</h2>
					
					<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post"> 

					<span id = "buttons">
						<input type = "button" value="Create New Question" onclick="addQuestion()">
						<input type="hidden" id="str" name="str" value="" /> 
						<input type="submit" id="btn" name="submit" value="Finish Survey" onclick="createArray()"/>
 					</form>
 					<footer>
	                    <input type="button" class="home" onclick="leavePage()" value="Return to HomePage">
	                </footer>



					<?php

                    

                    if(isset($_POST['submit'])){                    	 
                    	//create a form
						$insert_stmt = $mysqli->prepare("INSERT INTO form_tbl (form_admin) VALUES (?)");
                        $insert_stmt->bind_param('i', $user_id);
                        // Execute the prepared query.
                        $insert_stmt->execute();
                        $stmt_count = $mysqli->prepare("SELECT form_id FROM form_tbl WHERE form_admin= ? ORDER BY form_id DESC LIMIT 1");
                        $stmt_count->bind_param('i',$user_id);
                        $stmt_count->execute();    
                        $stmt_count->store_result();
                        // get variables from result.
                        $stmt_count->bind_result($form);
                        $stmt_count->fetch();
                    	$type = 3;
                    	//--------------------------
						$str = json_decode($_POST['str'], true);
						$numberOfQuestions = intval($str[count($str)-1]); // convert to int
						$questionNum = 0;
						$index = 0;
						//var_dump($str);
						// str holds all values
						while($questionNum < $numberOfQuestions){
							$question = $str[$index];
							$index++;
							$numOfAnswers = intval($str[$index]); // convert to int
							$index++;
							// enter question in db
							$insert_stmt = $mysqli->prepare("INSERT INTO question_tbl (question_type, question_form, question_ask) VALUES (?, ?, ?)");
                            $insert_stmt->bind_param('iis', $type, $form, $question);
                            // Execute the prepared query.
                            $insert_stmt->execute();
							// retreive question id
							$stmt_count = $mysqli->prepare("SELECT question_id FROM question_tbl WHERE question_ask = ? AND question_form = ? LIMIT 1");
                            $stmt_count->bind_param('si',$question,$form);
                            $stmt_count->execute();    
                            $stmt_count->store_result();
                            // get variables from result.
                            $stmt_count->bind_result($question_id);
                            $stmt_count->fetch();
                            if ($stmt_count->num_rows == 1) {
							// loop through answers and enter in db
                            	for($i=0;$i<$numOfAnswers;$i++){
                            		$choice = $str[$index];
                            		$index++;
                            		$insert_stmt = $mysqli->prepare("INSERT INTO choices_tbl (choiceQuestion_id, choice) VALUES (?, ?)");
                            		$insert_stmt->bind_param('is', $question_id, $choice);
                            		// Execute the prepared query.
                            		$insert_stmt->execute();
                            	}
                            	
                            }
							// increment to next question
							$questionNum++;
						}

					

						echo '<script type="text/javascript">

							window.location = "GSUSurveyViewSurvey.php?survey='.$form.'"

							</script>';

					

					

					}

						

  					

                    ?>

				</span>

				<br><br> 

				<script>

						addQuestion();

				</script>

			<?php else : ?>

				<p>

                	<span class="error">You are not authorized to access this page.</span> Please <a href="index.php">login</a>.

            	</p>

        	<?php endif; ?>
        		


			</div>

		</div>

	</body> 

</html>

