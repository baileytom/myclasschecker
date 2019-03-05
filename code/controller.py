#!/usr/bin/env python

import MySQLdb
import smtplib
import urllib2
from postmarker.core import PostmarkClient

class Course:
    def __init__(self, crn, term, c_id, name):
        self.crn = crn
        self.term = term
        self.url = "https://mybanner.gvsu.edu/PROD/bwckschd.p_disp_detail_sched?term_in={0}&crn_in={1}".format(term, crn)
        self.c_id = c_id
        self.name = name

    def __repr__(self):
        return "{0}:{1}".format(self.crn, self.term)

    def get_id(self):
        return self.c_id
    
    def get_name(self):
        return self.name

    def get_crn(self):
        return self.crn

    def getHTML(self):
        response = urllib2.urlopen(self.url)
        html = response.read()
        return html

    def isOpen(self):
        # Boolean returns if the class has any open seats
        # Parse the HTML and find out the seats remaining
        html = self.getHTML()
        
        index = html.find('Seats')+40
        seats = ''     
        while html[index] not in '<>':
            seats += html[index]
            index += 1
        index += 28
        seats_taken = ''
        while html[index] not in '<>':
            seats_taken += html[index]
            index += 1
        seats_left = int(seats) - int(seats_taken)
        # Return true or false
        return(seats_left > 0)

class Email(object):
    def __init__(self, recipient, subject, body):
        self.recipient = recipient
        self.subject = subject
        self.body = body

    def __str__(self):
        return 'Subject: {}\n\n{}'.format(self.subject, self.body)

    def get_subject(self):
        return self.subject

    def get_body(self):
        return self.body

    def get_recipient(self):
        return self.recipient

class WelcomeEmail(Email):
    def __init__(self, recipient):
        self.recipient = recipient
        self.subject = "Welcome to myclasschecker.com!"
        self.body = """
        <html>
        <body>
        Hello, and thank you for signing up at myclasschecker.com!<br>
        <br>
        Here's how it works:
        <ul>
        <li><a href="myclasschecker.com/login.php">Login</a> with your email & password.</li>
        <li>Add the full courses you want to track by CRN, term & year.</li>
        <li>You're done! When your classes open up we'll send you an email.</li>
        </ul>
        Remember, myclasschecker.com is the <b>only</b> free automated class checker designed for GVSU.
        <br>
        <br>
        Check on!

        <br>
        -- Tom
        </body>
        </html>
        """

class NotificationEmail(Email):
    def __init__(self, recipient, course):
        self.recipient = recipient
        self.course = course
        self.subject = """Class available! | {0}""".format(course.get_name())
        self.body = """
        <html>
        <body>
        <p> Hello, and thank you for using myclasschecker.com!
        <br><br>
        Your course just became available!
        <br><br>
        Register here: {1}
        <br><br>
        Steps:
        <ol>
        <li>Copy this CRN: {2}</li>
        <li>Log in with your G number & password</li>
        <li>Click Student -> Add or Drop Classes</li>
        <li>Select term</li>
        <li>Paste the CRN {2} in Add Classes Worksheet under CRNs</li>
        <li>Click 'Submit Changes'</li>
        <li>You're done!</li>
        </ol>
       
        <p>If another checker got into this class before you, remember to re-add the course at myclasschecker.com.</p>
        
        Check on!<br>
        -- Tom
        </body>
        </html>
        """.format(course.get_name(), "https://mybanner.gvsu.edu", course.get_crn())

class ResetEmail(Email):
    def __init__(self, recipient, token):
        self.recipient = recipient
        self.token = token
        self.subject = """Password Reset | myclasschecker.com"""
        self.body = """
        <html>
        <body>
        <p>Enter this code at <a href="myclasschecker.com/resetcode.php">myclasschecker.com</a> to reset your password:</p>
        <blockquote>{0}</blockquote>
        Check on!<br>
        -- Tom
        </body>
        </html>
        """.format(self.token)

class EmailBot(object):
    def __init__(self):
        
        self.useremail = 'tom@myclasschecker.com'
        self.token = 'fake_token'
                
        self.postmark = PostmarkClient(server_token=self.token)
        
    def send(self, email):
        self.postmark.emails.send(
            From=self.useremail,
            To=email.get_recipient(),
            Subject=email.get_subject(),
            HtmlBody=email.get_body()
            )
        

class DataBot(object):
    def __init__(self):
        self.host = 'localhost'
        self.username = 'classcheckerapp'
        self.password = 'fake_password'
        self.db = 'my_data'

        self.conn = MySQLdb.connect(host=self.host,
                                    user=self.username,
                                    passwd=self.password,
                                    db=self.db)

        self.cur = self.conn.cursor()

    def get_queue(self):

        
        #self.cur.execute("LOCK TABLE EmailQueue READ")
        
        self.cur.execute("SELECT recipient, passwordreset, token FROM EmailQueue")
        result = self.cur.fetchall()
        self.clear_queue()
        myoutbox = []
        for row in result:
            if row[1]: #if it's a password reset email
                myoutbox.append(ResetEmail(row[0], row[2]))
            else:
                myoutbox.append(WelcomeEmail(row[0]))

        #self.cur.execute("UNLOCK TABLES");

        return myoutbox

    def clear_queue(self):
        self.cur.execute("DELETE FROM EmailQueue")
        self.conn.commit()

    def get_classes(self):
        self.cur.execute("SELECT crn, term, id, name FROM Classes WHERE EXISTS (SELECT class_id FROM Subscriptions WHERE class_id = Classes.id)")
        classes = []
        for row in self.cur.fetchall():
            classes.append(Course(row[0], row[1], row[2], row[3]))
        return classes

    def get_subscribers(self, course):
        self.cur.execute("SELECT email FROM Users as u, Subscriptions as s WHERE u.id = s.user_id AND s.class_id = {0}".format(course.get_id()))
        subscribers = []
        for row in self.cur.fetchall():
            subscribers.append(row[0])
        return subscribers

    def clear_subscribers(self, course):
        self.cur.execute("DELETE FROM Subscriptions WHERE class_id = {0}".format(course.get_id()))
        self.conn.commit()

    def log_email(self, email):
        self.cur.execute("INSERT INTO EmailLog (recipient, subject) VALUES ('{}', '{}')".format(str(email.get_recipient()), str(email.get_subject())))
        self.conn.commit()

EmailBot = EmailBot()
DataBot = DataBot()

outbox = []
# Get the list of courses with at least one subscriber
classes = DataBot.get_classes()

# Loop through each class
for course in classes:
    status = course.isOpen()
    # If it's open
    if status:
        # Get a list of subscribers to the course
        subscribers = DataBot.get_subscribers(course)
        # Add emails for each subscriber to the outbox
        for subscriber in subscribers:
            email = NotificationEmail(subscriber, course)
            outbox.append(email)
        # Clear the subscribers
        DataBot.clear_subscribers(course)
        
        

# Add the welcome & password reset emails to the outbox
outbox += DataBot.get_queue()

# Clear the email queue table
#DataBot.clear_queue()

# Send and log the emails
for email in outbox:
    EmailBot.send(email)
    DataBot.log_email(email)
    
