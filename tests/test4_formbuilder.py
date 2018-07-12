import time
from selenium import webdriver
from random import *
from testconfig import *
from selenium.webdriver.common.keys import Keys

driver = webdriver.Chrome('C:/Users/Kyle/Desktop/chromedriver_win32/chromedriver.exe')  # Optional argument, if not specified will search path.


'''
Test Title: Admin create a form for the second page


Test Setup:

1. None


Test Steps:


1. Login to Moodle
2. Make new form


Results:

New form is created in cmanager (page2.phg form)

'''



driver.get('http://localhost/moodle-3.5/moodle/blocks/cmanager/formeditor/form_builder.php');
#time.sleep(1) # Let the user actually see something!

####### Moodle Auth ###################
search_box = driver.find_element_by_name('username')
search_box.send_keys(TEST_ACC_USERNAME)

search_box = driver.find_element_by_name('password')
search_box.send_keys(TEST_ACC_PASSWORD)

search_box.submit()



# enter new form name

search_box = driver.find_element_by_id('newformname')
search_box.send_keys('sample form name here')

# call the function to create the form.
a = driver.execute_script("return addNewField()")

# leave page message 
alert = driver.switch_to_alert()
alert.accept()

#driver.quit()


