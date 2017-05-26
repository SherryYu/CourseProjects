import numpy as np
import cv2


class Calibrate:
    # world object points(hand)
    objectpoints = np.array([[[-6.4, 7.5, 0], [-3.4, 10.7, 0], [-2.8, 3.5, 0],
                              [-3, 11, 0], [-0.3, 11.7, 0], [-0.8, 4.7, 0],
                              [0.3, 11.7, 0], [4.0, 10, 0], [1.3, 4.5, 0],
                              [4.7, 10, 0], [7.7, 3.4, 0], [3.6, 1, 0]]], dtype=np.float32)

    def __init__(self):
        self.MTX = np.loadtxt('./intrinsic/matrix.out', dtype=np.float32)  # calibration matrix
        self.DIST = np.loadtxt('./intrinsic/dist.out', dtype=np.float32)  # distortion
        self.RVEC = [] # rotation
        self.TVEC = [] # translation
        self.frame = None

    def drawAxis(self, img, axispts, frame):
        # center = tuple(axispts[3].ravel())
        # cv2.line(img, center, tuple(axispts[0].ravel()), (255, 0, 0), 5)
        # cv2.line(img, center, tuple(axispts[1].ravel()), (0, 255, 0), 5)
        # cv2.line(img, center, tuple(axispts[2].ravel()), (0, 0, 255), 5)
        imgpts = np.int32(axispts).reshape(-1, 2)

        # draw pillars

        # draw roof
        cv2.drawContours(img, [imgpts[:4]], -1, (0, 100, 255), 4)
        for i in (range(4)):
            cv2.line(img, tuple(imgpts[i]), tuple(imgpts[4]), (0, 100, 0), 4)


        for i in range(0,5):
            imgpts[i][0] = imgpts[i][0] + 0.5 * frame.shape[1]

        # cv2.drawContours(frame, [imgpts[:4]], -1, (0, 100, 255), 4)
        # for i in (range(4)):
        #     cv2.line(frame, tuple(imgpts[i]), tuple(imgpts[4]), (0, 100, 0), 4)
        # cv2.imshow('original', frame)

    def calibrate(self, imagepoints, drawing, frame):
        self.frame = drawing
        gray = cv2.cvtColor(drawing, cv2.COLOR_BGR2GRAY)
        # slove for extrinsic parameters
        ret, rvecs, tvecs = cv2.solvePnP(self.objectpoints, imagepoints, self.MTX, self.DIST)
        # pyramid points
        axis = np.float32(
            [[-2, -2, 0], [-2, 2, 0], [2, 2, 0], [2, -2, 0], [0, 0, -4]])
        axispoints, _ = cv2.projectPoints(axis, rvecs, tvecs, self.MTX, self.DIST)
        self.drawAxis(drawing, axispoints, frame)
        self.RVEC = rvecs
        self.TVEC = tvecs

    # projection matrix in opengl
    def getGLP(self):
        height = self.frame.shape[0]
        width = self.frame.shape[1]
        P = np.zeros(shape=(4, 4), dtype=np.float32)

        fx = self.MTX[0, 0]
        fy = self.MTX[1, 1]

        cx = self.MTX[0, -1]
        cy = self.MTX[1, -1]

        near = 0.1
        far = 100.0

        P[0, 0] = 2 * fx / width
        P[1, 1] = 2 * fy / height
        P[0, 2] = 1 - (2 * cx / width)
        P[1, 2] = (2 * cy / height) - 1
        P[2, 2] = -(far + near) / (far - near)
        P[3, 2] = -1.
        P[2, 3] = -(2 * far * near) / (far - near)

        p = P.T
        return p.flatten()

    # view matrix in opengl
    def getGLM(self):
        R, _ = cv2.Rodrigues(self.RVEC)
        Rt = np.hstack((R, self.TVEC))
        Rx = np.array([[1, 0, 0], [0, -1, 0], [0, 0, -1]])
        M = np.eye(4)
        M[:3, :] = np.dot(Rx, Rt)

        m = M.T
        return m.flatten()

    def getGLPM(self):
        glP = self.getGLP()
        glM = self.getGLM()
        return glP, glM