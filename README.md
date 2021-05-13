# Web Outline
Without a session, it starts from a welcome page with links to login or register page in the navigation bar. For a new user, when he or she finishes registration, the system automatically log him or her in.
Then with a session, it starts from a overview page that render information stored for the user:
* for providers, the web render all spot in a the available time slot that provider provides. One record represents a spot in that time slot;
* for patients, the web render all the preference of time slots for the patient;
* for the administrator, the web render two tables, one for appointment assignment, the other for priority group assignment.




# Features & Implementation
* The automatic assignment of appointment is expected to perform using cron job on the same machine in which the database locates.
* The manual assignment of apointment can be performed by the administrator.
* In the register and login page, there is real-time feedback that prompts for users if the web page detects a wrong format input in specific fields
* After a user is registered, the server dynamically detects the user type (provider or patient).
* Captcha mechanism for verification of human
* Update of user profile is allowed, except for SSN and email.




# Techniques & Tools
We use primarily HTML for the components in the web page, along with jQuery 1.11 and node.js for dynamic content, like the update on the table of the primary operation page (index.php). We use CSS and Bootstrap 3 for styling, choose MySQL as our DBMS, communicate with it using PHP 7.4, and Node.js for data processing and jQuery for user input data validation.

We use Python 3.8 for the algorithm that automatically assigns appointment, and use PHP script to execute the algorithm, which is expected to be handled and executed regularly by the cron job.




# Mechanism of Appointment Assignment & Algorithm Details
PHP first prepare the query results of provider available time and patient preferred time, and share them to python program by storing them as json file (*assigner/pat\_rows.json* and *ppt\_rows.json*). After python script, the processed data (assigned appointment) are passed back the same way it receives (*assigner/appointment.json*).

In the core python script, in addition to the records, which are saved as a dictionary, that relate patients to their preferred time (let's call this relationship ppt, which stands for patient preferred time), which is retrived from *ppt\_rows.json*, it establishes another dictionary that relate each time slot back to the patient (let's call it ppt\_reverse). Plus, the records that are also saved as a dictionary.
We start from the patient with the least preferred time slots (if there are multiple competitors, we choose the smallest patient\_id which represents the earlist registered one. Iterate through his or her preferred time slots to find a provider within his or her expected maximum distance that provides the time slot.
* If we find a match, we record it into the buffer.
* Otherwise, we skipped this patient for now.




# File Structure
* main.php (without session)
	Welcome page with link to login/register page on the navigation bar.
	* *public/js/check\_register.js*
		Check formats of register information.
		ssn: numeric & exact 10-char length & no repetition (for each pressed key released)
		email: *@*.* & no repetition (when lose focus)
		password: at least 6-char length & confirmed (for each pressed key released)
		
* index.php (with session)
	Primary operation page.
	For providers, it allows them to add/remove available time slot, for each of which is assigned a unique id and represents a spot for a specific time slot.
	For patients, it allows them to add/remove preferred time slot, and each preferred time slot will be recorded only once.
	For the administrator, it allows them to manually assign a available time slot to a patient with that preferred time slot recorded. Further, it allows the administrator to manually assign a patient a priority group.
	For all the users, there is a link to a page to update profile, in which the ssn and the user account (email) used for login are not allowed to be changed.
	* *public/js/update\_profile.js*
		Update the profile information for the user.
	* *public/js/appointment.js*
		Check the eligibility based on the user's priority group and the current date.
	* *public/js/editable.js*
		Query and render the table according to user type.
		For providers, it lists all the available time slot, and each record represents a spot for that time slot.
		For patients, it lists all the preferred time slots for the users. When an appointment is assigned to this user, an action is expected to be performed from this user.
		For administrator, there will be two tables, one for assignment of appointment, the other for assignment of priority group.
	* plugin/bootstable.js
		Add the functionality that allows users to directly update or remove a record/row.
		There are two buttons added to the end of each record, one for update, the other for deletion. When the one for update is pressed, there are two buttons following, one to save the change, the other to waive the change.
		
* *update\_table\_action.php*
	For each addition/update/remove of a record on the table in the primary operation page (*index.php*), it sends a `INSERT/UPDATE/DELETE` query to database for corresponding modification on database.
	
* *config.php*
	Connect to the database.
	
* *get\_geocode.php*
	When a user enter an address, it applies an geographical api that calculates the longitude and the latitude.
	
* *layouts/footer.php*
* *layouts/header.php*
* *layouts/nav.php*
	They are extracted from each page for modularity, which serve for footer, header, and the navigation bar.
	
* *admin/Login.php*
* *admin/Logout.php*
	They are in charge of login session of a user. If a user leaves the page, there is a session cookies that is valid for an hour.
	*Login.php* validates the login credential that a user provides by referencing the database.
	
* *admin/Register.php*
	It is in charge of registration of a user that check the format using *public/js/check\_register.js*, and add this user into the database if the validation are passed.
	
* *admin/Captcha.php*
	An additional mechanism that avoids robots or zombies.
	
* *admin/Appointment.php*
	It is in charge of querying/preparing all the information that is rendered in the primary operation page. The SQL query is based on the user type when a user logins.




# Revision from Project I
We split attribute timeslot\_id that ranges from 1 to 21, 3 time slots for each day, 7 days for each week, into week id (wid) that ranges from 1 to 7, and slot id (sid) that ranges from 1 to 3, representing 08:00-12:00, 12:00-16:00, and 16:00-20:00.




# Github
github.com/Rollingkeyboard/vacc\_appointment.git
