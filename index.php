<?php
/**
 * This is the home site of Shaun Burdick.  I am a Software Engineer, IT 
 * Professional, husband and father. I built this site as an online resume/cv.
 * I have gone through many iterations, trying out different solutions such 
 * as CMSs (Drupal, Wordpress, Joomla) and custom built DB driven content 
 * managers. In the end I was unhappy with any one solution, mostly because 
 * I felt the complexity was a bit overkill. Don't get me wrong, CMSs are very 
 * important as content providers and I build custom ones all the time for my 
 * work.  I just felt, for a single page with mostly static data, that a static 
 * solution is best.
 * For the design I went with my preference(after all, it is my site!). This 
 * page contains no graphics and is fast.  The design relies on CSS for layout 
 * with a little jQuery added for effect. I couldn't come up with a decent 
 * background and decided to emphasize my belief that source code can be 
 * beautiful. Consider it a quasi-Quine example.
 * Perhaps in the future I will add little easter eggs discoverable by reading 
 * this code to show off my craftiness, but for now I give all my craftiness 
 * to my family and my employer.
 * @author Shaun Burdick
 */

if(isset($_POST['test']))
{
  /**
   * Form was submitted, process and return result
   */
  header('Cache-Control: no-cache, must-revalidate');
  header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
  header('Content-type: application/json');
  
  $retVal = array
  (
      'result' => 'error',
      'msg' => ''
  ); 
  
  if(isset($_POST['email'], $_POST['message']) && intval($_POST['test']) === 9
     && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL))
  {
    /**
     * Humans can figure this out pretty easily...
     * Then again, I am not trying to stop humans!
     */
    $to = base64_decode('c2l0ZS5pbnF1aXJ5QHNoYXVuYnVyZGljay5jb20=');
    $headers = "From: {$_POST['email']}";
    $subject = "General Inquiry";
    if(mail($to, $subject, $_POST['message'], $headers))
    {
      $retVal['result'] = 'success';
      $retVal['msg'] = 'Email was sent successfully';
    }
    else
      $retVal['msg'] =  "Error sending email.";
  }
  else
    $retVal['msg'] = "Please fill in all fields correctly";
  
  print json_encode($retVal);
  exit;
}

/**
 * Get the formatted source for this page
 */
$source = highlight_file(__FILE__, true);

/**
 * begin html
 */
?>
<!DOCTYPE html>
<html>
   <head>
      <meta charset="UTF-8">
      <title>Shaun.Burdick</title>
      <script 
        src="http://ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js">
      </script>
      <script type="text/javascript">
        /**
         * toggleEmailFrom will toggle the email form div
         */
        function toggleEmailForm()
        {
          $('div#emailFormWrapper').slideToggle('fast');
        }
        
        /**
         * toggleResume will toggle the resume and display the source
         */
        function toggleResume()
        {
          if($('#resume').is(':visible'))
          {
            $('#resume').slideUp(400, function ()
            {
              $('div#source').removeAttr('style')
                             .removeClass('faded');
            });
          }
          else
          {
            $('#resume').slideDown(400, function ()
            {
              $('div#source').height($('div#info').height()+100 + 'px')
                             .addClass('faded');
            });
          }
        }
        
        /**
         * sendEmail will make the ajax request to send an email
         * and handle any errors that may occur
         */
        function sendEmail()
        {
          $.ajax(
          {
            target: '/',
            data: $('form#emailForm').serialize(),
            type: 'POST',
            success: function(data)
            {
              if(data.result && data.msg)
              {
                if(data.result == 'success')
                {
                  alert(data.msg);
                  $('div#errorMsg').text("");
                  $('textarea#message').val('');
                  toggleEmailForm();
                }
                else
                {
                  $('div#errorMsg').text(data.msg);
                }
              }
              else
              {
                $('div#errorMsg').text("An unexpected error occured. RUN!");
              }
            },
            error: function()
            {
              $('div#errorMsg').text("An unexpected error occured. RUN!");
            }
          });
        }
        
        /**
         * Modify the DOM once it is complete
         */
        $(document).ready(function () 
        {
          //Limit the size of the background code to a litte more than the
          //resume
          $('div#source').height($('div#info').height()+100 + 'px');
          
          //Register handler for submit action
          $('form#emailForm').submit(function(e)
          {
            e.preventDefault();
            sendEmail();
            return false;
          });

          //Move the email form to just under the email link
          var emailButtonOffset = $('a#email').offset();
          emailButtonOffset.top += $('a#email').height();
          /**
           * the div needs 'mass' in order to position it properly
           * this is why initially I use css visibility because items
           * that are visibile:hidden still take up "space" in the DOM
           */
          $("div#emailFormWrapper").offset(emailButtonOffset)
                                   .css('display','none')
                                   .css('visibility','visible');

          /** Fade out the click me **/
          $('#clickme').delay(2000).fadeOut('slow', 'swing');
        });
      </script>
      <style type="text/css">
        /*
        Colors Used:
        #DF0101: Red Highlight
        #F4FA58: Red Alt
        #EEEEEE: Grey
        #CCCCCC: Dark Grey
        #999999: Charcoal
        #0066CC: Blue, Primary Foreground
        #FFFFCC: Cream, Primary Font color
        */
        
        span.links
        {
          font-size: 0.50em;
        }
        span.links a
        {
          color: #DF0101;
          text-decoration: none;
          background-color: #FFFFCC;
          padding: 0px 5px;
        }
        span.links a:hover
        {
          color: #DF0101;
        }
        #main_wrapper
        {
          position: relative;
        }
        #source
        {
          background-color: #EEEEEE;
          border: 1px solid #CCCCCC;
          overflow: hidden;
          position: absolute;
          width: 100%;
          z-index: -1000;
          top: 0px;
          padding: 5px;
        }
        .faded
        {
           opacity: 0.4;
           filter: alpha(opacity=40);
        }
        #info_wrapper
        {
          margin: auto;
          position: relative;
          text-align: center;
          z-index: 1000;
          padding: 20px 100px;
        }
        #info
        {
          background: #0066CC;
          border: 1px dashed #999999;
          text-align: left;
          padding: 5px 5px 10px 10px;
          color: #FFFFCC;
          border-radius: 10px;
        }
        #minimize
        {
          text-align: right;
          font-size: 2em;
          line-height: 5px;
          font-weight: bold;
          padding-right: 5px;
        }
        div#minimize span#clickme
        {
          font-size: 0.6em;
          padding-top: 6px;
        }
        div#minimize a
        {
          text-decoration: none;
          color: #FFFFCC;
        }
        div#minimize a:hover
        {
          text-decoration: none;
          color: #FFFFCC;
        }
        table.twoColumn
        {
         width: 100%;
         border: none;
        }
        table.twoColumn tr td:last-child
        {
         text-align: right;
        }
        div#emailFormWrapper
        {
          z-index: 1000;
          position: absolute;
          padding: 5px;
          width: 400px;
          height: 375px;
          background-color: #FFFFCC;
          color: #0066CC;
          border: 2px dashed #DF0101;
          visibility: hidden;
        }
        form#emailForm input[type="text"], form#emailForm textarea
        {
          width: 390px;
        }
        div#errorMsg
        {
          background-color: #DF0101;
          margin: 5px 20px;
          text-align: center;
        }
      </style>
   </head>
   <body>
      <div id="main_wrapper">
         <div id="info_wrapper">
            <div id="info">
              <div id="minimize">
                <span id="clickme">Click Here &rarr;</span><a href="javascript:toggleResume()">-</a>
              </div>
              <div id="resume">
              <h2>
                Shaun Burdick
                <span class="links">
                  <a id="email" href="javascript:toggleEmailForm();">Email</a>
                  <a href="http://www.linkedin.com/in/shaunburdick">LinkedIn</a>
                  <a href="http://www.facebook.com/shaunburdick">Facebook</a>
                  <a href="https://github.com/shaunburdick">GitHub</a>
                </span>
              </h2>
              <hr/>
              <h3>Goal:</h3>
              <p>
               To broaden my knowledge of Information Technology while 
               sharpening my Customer Service ability. I enjoy learning new 
               technologies and staying on the cutting edge. I employ that 
               knowledge and drive in everything I do.
              </p>
              <h3>Education</h3>
              <table class="twoColumn">
                <tbody>
                  <tr>
                    <td>
                      <em>Alfred University</em> (08/2003-05/2005)<br/>
                      Alfred, NY
                    </td>
                    <td>
                      Major: Computer Science<br/>
                      Degree: Bachelor of Arts
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <em>Onondaga Community College</em> (08/2000-05/2003)<br/>
                      Syracuse, NY
                    </td>
                    <td>
                      Major: Computer Science<br/>
                      Transfer: Alfred University
                    </td>
                  </tr>
                </tbody>
              </table>
              <h3>Employment</h3>
              <em>Time Warner Cable</em><br/>
              <strong>Employment:</strong> October 2010 - Present<br/>
              <strong>Title:</strong> Software Engineer<br/>
              <strong>Supervisor:</strong> Mark Wynkoop<br/>
              <strong>Responsibilities:</strong>
              <ul>
                <li>
                  Design and maintain a suite of tools used by members 
                  of the East Region for internal customer support.  The tools
                  are primarily designed in PHP using MySQL for data storage.
                  I built a framework that handles permissions, theming, DB 
                  connections, and email/spreadsheet/graph generation.  The 
                  tools could then be developed separately from the framework
                  and incorporated as modules with separate versioning and 
                  dependencies.
                </li>
                <li>
                  Design and maintain a group of scripts that generate reports 
                  combining a menagerie of data sources.  These scripts are 
                  primarily designed in Perl/PHP and combine Oracle/MySQL 
                  databases with flat file exports to create 
                  spreadsheets/graphs to be emailed to lists of users.
                </li>
                <li>
                  Maintain development environment including Subversion 
                  repositories for various collections of projects.
                </li>
              </ul>
              <em>CGI</em><br/>
              <strong>Employment:</strong> March 2006 - October 2010<br/>
              <strong>Title:</strong> Senior Operator<br/>
              <strong>Supervisor:</strong> Steven LaManche<br/>
              <strong>Responsibilities:</strong>
              <ul>
                <li>
                  Created and maintained users, workstations, printers and GPO
                  in a global Active Directory
                </li>
                <li>
                  Responsible for the support of 400 workstations/700 users
                </li>
                <li>
                  Setup Automation scripts and designed an imagining process 
                  using Ghost Cast Server
                </li>
                <li>
                  Handled file and access permissions on Windows NT/2000/2003 
                  servers
                </li>
                <li>
                  Maintained WSUS approvals
                </li>
                <li>
                  Responded to trouble tickets in a strict SLA driven factory 
                  environment
                </li>
                <li>
                  Developed/Programmed multiple custom tools including a system 
                  information tool for a national AD rollout
                  <ul>
                    <li>
                      Developed a PHP/MySQL Inventory system that tracked all 
                      computers and computer related transactions.
                    </li>
                  </ul>
                </li>
                <li>
                  Built a Linux server for Apache PHP/MySQL application hosting
                  <ul>
                    <li>
                      Designed, Themed a Drupal Knowledge base for Desktop 
                      Support
                    </li>
                    <li>
                      Setup, Administrated Apache vHosts for additional tools
                    </li>
                  </ul>
                </li>
              </ul>
              <em>TEKSystems</em><br/>
              <strong>Employment:</strong> June 2005 - March 2006<br/>
              <strong>Recruitment Manager:</strong> John Morsheimer<br/>
              <strong>Responsibilities:</strong> 
              <em>See CGI Responsibilities</em>
              </div><!--/resume-->
            </div><!--/info-->
         </div><!--/info_wrapper-->
         <div id="emailFormWrapper">
           <div id="errorMsg"></div>
           <form id="emailForm">
             <label for="email">Email Address:</label>
             <input type="text" id="email" name="email"></input><br/>
             <label for="message">Message:</label><br/>
             <textarea id="message" name="message" rows="10"></textarea><br/>
             <label for="test">four plus seven minus two?</label>
             <input type="text" id="test" name="test"></input><br/><br/>
             <input type="submit" value="Send"></input>
             <a href="javascript:toggleEmailForm()">Cancel</a>
           </form>
         </div>
         <div id="source" class="faded">
           <?php echo $source;?>
         </div><!--/source-->
      </div><!--/main_wrapper-->
   </body>
</html>
