import time
from selenium import webdriver
from testconfig import *

from random import *
driver = webdriver.Chrome('C:/Users/Kyle/Desktop/chromedriver_win32/chromedriver.exe')  # Optional argument, if not specified will search path.
driver.get('http://localhost/moodle-3.5/moodle/blocks/cmanager/course_request.php?mode=1');

'''
Test Title: Simple course request.


Test Setup:

1. Add a second page to the request process that has ONE single text area
for content to be entered into.

2. enter enrolment key option turned on.

Test Steps:


1. Login to Moodle
2. Make new course request
3. enter in programme code
4. enter in programme title
5. enter in enrolmeny key.

6. second page for additional information, add details.


Results:

One new requst should be added with the expected content.
'''


#time.sleep(1) # Let the user actually see something!

####### Moodle Auth ###################
search_box = driver.find_element_by_name('username')
search_box.send_keys(TEST_ACC_USERNAME)

search_box = driver.find_element_by_name('password')

search_box.send_keys(PASSWORD)

search_box.submit()


#time.sleep(5) # Let the user actually see something!

########### Fill in the forms ####################
search_box = driver.find_element_by_name('programmecode')
search_box.send_keys('test\\\\\'\#\#1' + str(randint(1, 1000))  + '')

search_box = driver.find_element_by_name('programmetitle')
search_box.send_keys('this course\' \#]' + str(randint(1, 1000)) )

search_box = driver.find_element_by_name('enrolkey')
search_box.send_keys('5555')

search_box.submit()

# ------- second page during request -------------------------------
search_box = driver.find_element_by_name('f1')
search_box.send_keys('this is the default text // box that cmanager comes with 1#2222\' sdfsdfdsf')
search_box.submit()

# ------- reviewing the request ------------------------------------
time.sleep(3)
search_box = driver.find_element_by_name('submitbutton')
search_box.submit()


time.sleep(2)
search_box = driver.find_element_by_name('submitbutton')
search_box.submit()


#search_box.submit()
#time.sleep(5) # Let the user actually see something!

#driver.quit()