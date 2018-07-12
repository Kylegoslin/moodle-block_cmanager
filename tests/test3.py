import time
from selenium import webdriver
from random import *
from testconfig import *

driver = webdriver.Chrome('C:/Users/Kyle/Desktop/chromedriver_win32/chromedriver.exe')  # Optional argument, if not specified will search path.


'''
Test Title: User leaving comments on a module, then admin leaving comments.


Test Setup:

1. Request must already exist in the system.
2. Set the MOD_ID and the APPEND_TEXT variables below to to the comments


Test Steps:


1. Login to Moodle
2. Open the editor for the existing request (mode 2, edit = id of request) http://localhost/moodle-3.5/moodle/blocks/cmanager/comment.php?type=userq&id=58


3. Then switch to admin comment feature http://localhost/moodle-3.5/moodle/blocks/cmanager/comment.php?type=userq&id=



Results:

Comment should be left on the request, then the admin comment should be left.
Both comments should be viewable.
'''
MOD_ID = 60
APPEND_TEXT = 'this hmm is a sample appended \' that was left#2222 by the/person'

driver.get('http://localhost/moodle-3.5/moodle/blocks/cmanager/comment.php?type=userq&id='+str(MOD_ID));
#time.sleep(1) # Let the user actually see something!

####### Moodle Auth ###################
search_box = driver.find_element_by_name('username')
search_box.send_keys(TEST_ACC_USERNAME)

search_box = driver.find_element_by_name('password')
search_box.send_keys(TEST_ACC_PASSWORD)

search_box.submit()







# ------- user leave a comment -------------------------------
search_box = driver.find_element_by_name('newcomment')
search_box.send_keys(str(APPEND_TEXT))
search_box.submit()

# --- review the comment is there -------------
time.sleep(3)


# --------- admin leave a comment ------------------------


driver.get('http://localhost/moodle-3.5/moodle/blocks/cmanager/admin/comment.php?type=adminq&id='+str(MOD_ID));

search_box = driver.find_element_by_name('newcomment')
search_box.send_keys(str(APPEND_TEXT))
search_box.submit()
#driver.quit()


