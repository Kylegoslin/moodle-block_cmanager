import time
from testconfig import *
from selenium import webdriver
from random import *
driver = webdriver.Chrome('C:/Users/Kyle/Desktop/chromedriver_win32/chromedriver.exe')  # Optional argument, if not specified will search path.


'''
Test Title: User editing existing request


Test Setup:

1. Request must already exist in the system.
2. Set the EDIT_ID and the APPEND_TEXT variables below to add onto a request
3. enter enrollment key turned on


Test Steps:


1. Login to Moodle
2. Open the editor for the existing request (mode 2, edit = id of request) http://localhost/moodle-3.5/moodle/blocks/cmanager/course_request.php?mode=2&edit=43




Results:

Original request should now have the APPENDED_TEXT added onto it.
'''
EDIT_ID = 60
APPEND_TEXT = 'rarr'

driver.get('http://localhost/moodle-3.5/moodle/blocks/cmanager/course_request.php?mode=2&edit='+str(EDIT_ID));
#time.sleep(1) # Let the user actually see something!

####### Moodle Auth ###################
search_box = driver.find_element_by_name('username')
search_box.send_keys(TEST_ACC_USERNAME)

search_box = driver.find_element_by_name('password')
search_box.send_keys(TEST_ACC_PASSWORD)

search_box.submit()




search_box = driver.find_element_by_name('programmecode')
#value = search_box.get_attribute('value')
search_box.send_keys(' ' + str(APPEND_TEXT))

search_box = driver.find_element_by_name('programmetitle')
#value = search_box.get_attribute('value')
search_box.send_keys(' ' + str(APPEND_TEXT))


search_box = driver.find_element_by_name('enrolkey')
#value = search_box.get_attribute('value')
search_box.send_keys(' ' + str(APPEND_TEXT))


search_box.submit()


# ------- second page during request -------------------------------
search_box = driver.find_element_by_name('f1')
search_box.send_keys(str(APPEND_TEXT))
search_box.submit()

#driver.quit()


