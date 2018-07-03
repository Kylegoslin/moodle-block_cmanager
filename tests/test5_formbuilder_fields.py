import time
from selenium import webdriver
from testconfig import *
from random import *
from selenium.webdriver.common.keys import Keys
from selenium.webdriver.support.ui import Select
driver = webdriver.Chrome('C:/Users/Kyle/Desktop/chromedriver_win32/chromedriver.exe')  # Optional argument, if not specified will search path.


'''
Test Title: Add fields to an existing form


Test Setup:

1. Run test4_formbuilder.py first
2.  set the --- > form ID below


Test Steps:


1. Login to Moodle
2. 


Results:



'''

FORM_ID = 55

driver.get('http://localhost/moodle-3.5/moodle/blocks/cmanager/formeditor/page2.php?id='+str(FORM_ID)+'&name=sample');
#time.sleep(1) # Let the user actually see something!

####### Moodle Auth ###################
search_box = driver.find_element_by_name('username')
search_box.send_keys(TEST_ACC_USERNAME)

search_box = driver.find_element_by_name('password')
search_box.send_keys(TEST_ACC_PASSWORD)

search_box.submit()




# select the drop down menu item we want to add a new from field
# in this case we are adding a text field.


select = Select(driver.find_element_by_name('newfieldselect'))
## select by visible text
select.select_by_visible_text('Text Field')

#driver.quit()


