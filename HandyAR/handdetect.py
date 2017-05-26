import numpy as np
import cv2
import math

class HandDetect:

    def __init__(self):
        self.bgModel = None
        self.doStart = False

    def setDoStart(self, status):
        self.doStart = status

    def removeBG(self, frame):
        fgmask = self.bgModel.apply(frame)
        # kernel = cv2.getStructuringElement(cv2.MORPH_ELLIPSE, (3, 3))
        # res = cv2.morphologyEx(fgmask, cv2.MORPH_OPEN, kernel)
        kernel = np.ones((3, 3), np.uint8)
        fgmask = cv2.erode(fgmask, kernel, iterations=1)
        bg = cv2.bitwise_and(frame, frame, mask=fgmask)
        return bg

    def detectFingers(self, res, drawing):  # -> finished bool, count: finger count
        #  convexity defect
        hull = cv2.convexHull(res, returnPoints=False)
        if len(hull) > 3:
            defects = cv2.convexityDefects(res, hull)
            if not (isinstance(defects, type(None))):
                count = 0
                p = []
                for i in range(defects.shape[0]):
                    s, e, f, d = defects[i][0]
                    start = tuple(res[s][0])
                    end = tuple(res[e][0])
                    far = tuple(res[f][0])
                    a = math.sqrt((end[0] - start[0]) ** 2 + (end[1] - start[1]) ** 2)
                    b = math.sqrt((far[0] - start[0]) ** 2 + (far[1] - start[1]) ** 2)
                    c = math.sqrt((end[0] - far[0]) ** 2 + (end[1] - far[1]) ** 2)
                    # calculate the angle
                    angle = math.acos((b ** 2 + c ** 2 - a ** 2) / (2 * b * c))  # cosine theorem

                    # angle less than 90 degree, treat as fingers
                    if self.doStart == True and angle <= math.pi / 2:
                        count += 1
                        p.append(start)
                        p.append(end)
                        p.append(far)
                        cv2.circle(drawing, start, 8, [200, 200, 0], -1)
                        cv2.circle(drawing, end, 8, [0, 0, 255], -1)
                        cv2.circle(drawing, far, 8, [211, 84, 0], -1)
                imagepoints = np.array([p], dtype=np.float32)
                if imagepoints.shape[1] == 12:
                    return imagepoints
        return []




