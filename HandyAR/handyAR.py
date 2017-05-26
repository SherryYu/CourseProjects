import numpy as np
import cv2
import copy
from calibrate import Calibrate
from handdetect import HandDetect
from display import DemoAR


#####
# Usage:
# 1. Run, choose a stable background, do not put you hand in
# 2. press 'b' to get a black contour window, then put your hand in
# 3. when you see a clear hand shape, press 's' to do calibration and draw axes
# 4. If background fails, press 'r' to reset background(remember not to put your hand in), then do the same as (2).
# 5. ignore the pygame window, it has to be there
# 6. ESC to exit
#####

class HandyAR:
    cap_region_x_begin = 0.5  # start point/total width
    cap_region_y_end = 0.8  # start point/total width
    threshold = 60  # BINARY threshold
    blurValue = 41  # GaussianBlur parameter
    bgSubThreshold = 50

    def __init__(self):
        self.isBgCaptured = 0  # bool, whether the background captured
        # Camera
        self.camera = cv2.VideoCapture(0)
        self.camera.set(10, 200)

        self.handDetect = HandDetect()
        self.calibrate = Calibrate()
        self.demo = None


    def run(self):
        while self.camera.isOpened():
            ret, frame = self.camera.read()
            frame = cv2.bilateralFilter(frame, 5, 50, 100)  # smoothing filter
            frame = cv2.flip(frame, 1)  # flip the frame horizontally
            cv2.rectangle(frame, (int(self.cap_region_x_begin * frame.shape[1]), 0),
                          (frame.shape[1], int(self.cap_region_y_end * frame.shape[0])), (255, 0, 0), 2)
            # half of the original frame for opengl operation
            halfframe = frame[0:int(self.cap_region_y_end * frame.shape[0]),
                      int(self.cap_region_x_begin * frame.shape[1]):frame.shape[1]]
            cv2.imshow('original', frame)

            if self.isBgCaptured == 1:  # this part wont run until background captured
                img = self.handDetect.removeBG(frame)
                img = img[0:int(self.cap_region_y_end * frame.shape[0]),
                      int(self.cap_region_x_begin * frame.shape[1]):frame.shape[1]]  # clip the ROI
                #cv2.imshow('mask', img)

                # convert image into binary image
                gray = cv2.cvtColor(img, cv2.COLOR_BGR2GRAY)
                blur = cv2.GaussianBlur(gray, (self.blurValue, self.blurValue), 0)
                #cv2.imshow('blur', blur)
                ret, thresh = cv2.threshold(blur, self.threshold, 255, cv2.THRESH_BINARY)
                #cv2.imshow('ori', thresh)

                # get coutours
                thresh1 = copy.deepcopy(thresh)
                contours, hierarchy = cv2.findContours(thresh1, cv2.RETR_TREE, cv2.CHAIN_APPROX_SIMPLE)
                length = len(contours)
                maxArea = -1
                if length > 0:
                    for i in range(length):  # find the biggest contour (according to area)
                        temp = contours[i]
                        area = cv2.contourArea(temp)
                        if area > maxArea:
                            maxArea = area
                            maxi = i
                    contour = contours[maxi]
                    hull = cv2.convexHull(contour)
                    drawing = np.zeros(img.shape, np.uint8)
                    cv2.drawContours(drawing, [contour], 0, (0, 255, 0), 2)
                    cv2.drawContours(drawing, [hull], 0, (0, 0, 255), 3)

                    imagepoints = self.handDetect.detectFingers(contour, drawing)
                    if imagepoints.__len__() > 0:
                        self.calibrate.calibrate(imagepoints, drawing, frame)
                        glP, glM = self.calibrate.getGLPM()

                        # if you want to see calibration points, comment those lines
                        # draw objects on hand with opengl
                        self.demo = DemoAR(halfframe, glP, glM)
                        self.demo.main()

                cv2.imshow('output', drawing)


            # Keyboard OP
            k = cv2.waitKey(10)
            if k == 27:  # press ESC to exit
                exit(0)
            elif k == ord('b'):  # press 'b' to capture the background
                self.handDetect.bgModel = cv2.BackgroundSubtractorMOG2(0, self.bgSubThreshold)
                self.isBgCaptured = 1
                print('Background Captured')
            elif k == ord('r'):  # press 'r' to reset the background
                self.handDetect.bgModel = None
                self.isBgCaptured = 0
                self.handDetect.setDoStart(False)
                print('Reset BackGround')
            elif k == ord('s'):
                self.handDetect.setDoStart(True)
                print('Start Calibration')


handyAR = HandyAR()
handyAR.run()
