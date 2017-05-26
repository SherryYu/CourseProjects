import numpy as np
import cv2
from OpenGL.GL import *
from OpenGL.GLUT import *
from OpenGL.GLU import *
cap = cv2.VideoCapture(0)
i= 30
while(True):
    ret, frame = cap.read()
    gray = cv2.cvtColor(frame, cv2.COLOR_BGR2GRAY)
    # im = cv2.imread(gray)
    img = frame[0:int(0.8 * frame.shape[0]),0:int(0.5 * frame.shape[1])]
    cv2.imshow('img', img)
    k = cv2.waitKey(50)
    if k == 27:  # press ESC to exit
        break
    elif k == ord('a'):  # press 'b' to capture the background
        if (i>0):
            print i
            name = './image/image' + str(i) + '.jpg'
            cv2.imwrite(name, img)
            i = i - 1
        else:
            cap.release()
            cv2.destroyAllWindows()
            exit(0)
