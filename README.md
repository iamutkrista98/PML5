# PML5
# Demonstration Featured video available at https://www.youtube.com/watch?v=alBw4f9L2G4
Project Management Level 5 
this repository contains backend integration to the front end implementation done on DBQMART repository by different collaborators. 
The back end implementation is inclusive of PHP, PHPMailer, PayPal Sandboxing, oracle database throguh oci for dynamic content delivery

CUSTOMER INTERFACE(USER GUIDELINES)
⦁	First, you need to go to the homepage of DBQ MART and then can find Account option at the navigation bar. After clicking on the option, you are greeted with a page that provides 2 options, namely trader and customer. You are to select the customer option which takes you to the customer login page, at the bottom you find a ‘Not a customer’ Register Now colored link. Click on that and you are taken to the Customer Registration Page where you are required to fill the fields.
 

 

⦁	Then, login form is displayed.


⦁	If you already have an account then you can directly login into your account if not, you can create a new account as customer by filling in the register form.
 
⦁	After filling in the fields with valid information you’ll receive an email for verification. 
⦁	No details filled and pressed the register button you get respective error messages as depicted below
 
Further validations for each field
 
 
 
After all fields filled meeting, the appropriate standards press the register button
 

Verification email is sent to your respective email address
 
Click the verify button to verify your registration 
 


You will then be redirected to the login page
If you are not verified through email and try to login using just the registration credentials
An error saying, an error is displayed denying access. This error is also shown when your account has been flagged as inactive by the administration.

⦁	After being verified you’ll be able to login as customer in this website and your homepage will look something like this.    
 

 







You can also edit your details, update profile picture through the my account page. You can navigate to it by clicking on the username on the navbar.

 

 

 

 

 

Option to change password also made available:
 


           
⦁	You can also view About Us page which will look like this by navigating to the about us option in the navigation menu.
 

⦁	If you want to know about the traders in DBQ MART then click on About Us from the navigation bar.
 
⦁	You can also search products according to category
 

⦁	If you want to search for a specific product then you can type in the search field, also you have different sort and order by options. Choose the appropriate and press the filter button.
 
  

⦁	If you want to view products details then click on product.
 

⦁	You can also add reviews to the products after it has been purchased
 
 
 
⦁	If you want to add products to your wishlist then you can click on add to favourite button and you can view your Favourite products by clicking on your username in the navigation bar and then selecting wishlist option which will redirect you to wishlist page. Your wishlist page will look like this.
 
 

 

 
You can also remove items from wishlist
 
⦁	If you want to add items to the cart then click on ‘ADD TO CART’ button.
⦁	Add to cart
⦁	You can also update the quantity of items in the cart interface as depicted in screenshot below 
 
⦁	Quantity update 
 
Remove Product from cart by pressing the remove button
 
 
⦁	You can also add multiple items to cart and update respective quantity
⦁	Here you will also find that: 
⦁	Minimum quantity is carried over as the starting quantity for each product

⦁	Total quantity less than 20 checked before checkout is validated
⦁	When you add items to the cart, the items will appear in the Cart page like mentioned above                              

⦁	You’ll be notified about your order placed through email. After you proceed to checkout   

 
Confirm button pressed sends an email invoice on the customer email address
 

 

 

After reviewing, pressing the paypal button takes to the paypal checkout interface. Login with the credentials and you are greeted with the shop name and total amount to be paid

 


 

After successful payment from paypal

 

On Successful payment redirected, cart emptied, order confirmation receipt sent to your customer email

 

After this you can also view your orders from the check orders button as well as move on to your account page

 
On pressing check orders
 
On clicking the order id link, ordered product list shown
 

⦁	If you want to logout of your account then you can click on your profile username on the navigation menu and then click on logout icon next to it.




ADMIN ORACLE INTERFACE GUIDE:
 
⦁	At first you need to login with the admin credentials.
 

⦁	After login in with correct login details as Admin you’ll have access to the reports of all the traders, information of all customers, details of shop and products, information of highest sellers, top selling products and you can also view bunch of graphs, pie-charts, bar-charts, line charts as well as different reports pertaining to conveying proper information. 
At first you are greeted with a home page in the form of a dashboard containing information as highlights along with different informative charts. This has been depicted below in the following screenshots.
 

 

 



 

 

 

⦁	The side nav options have different sections, under reports you can find orders calendar, sales by product category, monthly sales report for the overall mart, payment report, ordered products, weekly finance report and trader specific monthly sales report generated on the basis of selecting the trader from a drop-down list.

The below is orders calendar
 
⦁	While hovering it contains information pertaining to the order total, customer that ordered, collection slot information etc for all the confirmed orders. They can be viewed by month, day week and even in list format.
 
⦁	The order id is a link which when clicked opens the order specific products ordered by customers by passing the order id into a modelled dialog box
 

⦁	By selecting the sales by product category option under reports displays a donut chart it displays the sales by product category in a graphical representation.
 
⦁	The monthly sales report option shows the monthly revenue generated by DBQ mart in both bar-graph and line chart representation.
 

 

⦁	The payment report section in the nav presents information pertaining to the payments made by customer on the basis of orders.
There is also the functionality of viewing the payment specific by order id.
 

 

⦁	There is also availability of weekly finance report
 






⦁	The trader wise monthly sales report section is based on supplying the trader name through a drop down to view their specific monthly sales information in a bar-chart as well as line chart.

 
 

 

 

Trader wise daily orders can also be viewed by navigating to the option trader wise daily orders where the admin can select the trader’s name from drop down to view their daily orders
 

⦁	Another section lies in the manage section where the admin is supplied with the following options.
 
⦁	View trader requests pertaining to registration made by trader to join dbq mart with option to approve request that takes in the details into a form that the admin can submit to approve the request for apex access, credentials would be same as sent to email for web access after approval by admin for the web interface.
 
 
⦁	Another section is for admin to manage the products performing crud operations. The interface is laid down in such a way
 

 

⦁	The manage customer section displays in this manner where the admin can manage customer account related tasks. The trader can also deactivate the account of customer by setting the status to 0.
 
⦁	The manage shop section allows managing the shops of traders with the ability to change the shop logo, banner image etc.
 

⦁	The admin can also view, edit and disable all the reviews and ratings posted by customer 

 

⦁	The manage trader option allows managing trader accounts
 

  






 

⦁	You can logout by clicking on username and then Sign out. 
 


More screenshots 
 
 
 
 TRADER(ORACLE)
⦁	In order to login, you need to enter the correct credentials supplied in order to login
 
If wrong credentials entered you get error like below
 
⦁	After logging in you are greeted with the following interface having options like viewing your own monthly sales report and daily orders
 
⦁	To view your monthly sales report, select the trader wise monthly sale report option 
  



⦁	To view your daily orders, select trader wise daily orders option and view your daily orders
 









⦁	To logout, click on the username at the top and select sign out
 



TRADER (PHP) GUIDE
 

⦁	First, you need to go to the homepage of DBQ MART and then you can find Account section at the navigation bar.
⦁	Once you click on the account section you are greeted with 2 options in the form of images, hover over to the images and you find that there on the left you find the trader option with hovering effect. Click on it and you will be directed to the registration page
 
 
Fill in the credentials and press login. You can also register as a trader by filling in a form, however it needs to get approved by the administrator after thorough verification.

⦁	Once the correct trader login credentials are filled, you will see a page with dashboard that’ll look something like this. You can also update your Shop Details, Personal Details as well as Contact Details and also perform CRUD operations of the products that are associated with you.
 

 
 

⦁	You can also change your profile/thumbnail image by choosing file
 

 


⦁	If you would like to manage your products select the manage products option on the side navigation menu, it’ll allow you to view all the product with their details. You can update or Delete Products, as well as add products.
 

⦁	In Order to edit product details, select the edit icon over respective table row in the edit column which will take you to the below mentioned interface where you can perform the required operations.

 

⦁	If you would like to add products, then navigate back to manage products menu option and on top you find a add product button, press on it and you will be redirected to the below depicted interface. Supply the necessary details to add products.
 

⦁	If you navigate to the view orders section, you can view the orders you have received and that are fulfilled or moreover all the orders history for the products you have received so far.

 
⦁	You can also view reviews and ratings posted by customer on the products that have been purchased by customers.

 



⦁	To logout, click the logout button on the left highlighted in pinkish accent color. This will take you back to the home page.
 
 

⦁	If the trader wishes to sign up then the sign up form has to be filled. They will then receive the email saying that their registration is under review. They have to sit back and wait for their registration to be either approved or denied

 
 

The admin gets an email as well that is similar to the one mentioned below
 

After admin approved the trader gets this email 

 

They can now login as mentioned priorly using the credentials supplied.




 
       
