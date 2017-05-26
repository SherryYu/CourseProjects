import cv2
import numpy as np
from OpenGL.GLUT import *
from objloader import *
from PIL import Image
import pygame
import os

def setProjection(glP):
    glMatrixMode(GL_PROJECTION)
    glLoadIdentity()
    glLoadMatrixf(glP)


def setModelview(glM, scale=1.):
    glMatrixMode(GL_MODELVIEW)
    glLoadIdentity()
    glLoadMatrixf(glM)
    glTranslate(0.5, 0.5, -1)
    glRotate(180, 0, 1, 0)
    glRotate(180, 0, 0, 1)
    glScalef(scale, scale, scale)


def drawBackground(frame):
    bg_image = pygame.surfarray.make_surface(frame)
    bg_image = pygame.transform.rotate(bg_image, 90.0)
    bg_data = pygame.image.tostring(bg_image, 'RGBA', False)
    width, height = bg_image.get_size()

    glMatrixMode(GL_MODELVIEW)
    glLoadIdentity()
    glClear(GL_COLOR_BUFFER_BIT | GL_DEPTH_BUFFER_BIT)
    glEnable(GL_TEXTURE_2D)
    glGT = glGenTextures(1)
    glBindTexture(GL_TEXTURE_2D, glGT)
    glTexImage2D(GL_TEXTURE_2D, 0, GL_RGBA, width, height, 0, GL_RGBA, GL_UNSIGNED_BYTE, bg_data)
    glTexParameterf(GL_TEXTURE_2D, GL_TEXTURE_MAG_FILTER, GL_NEAREST)
    glTexParameterf(GL_TEXTURE_2D, GL_TEXTURE_MIN_FILTER, GL_NEAREST)

    glBegin(GL_QUADS)
    glTexCoord2f(0.0, 0.0);
    glVertex3f(-1.0, -1.0, -1.0)
    glTexCoord2f(1.0, 0.0);
    glVertex3f(1.0, -1.0, -1.0)
    glTexCoord2f(1.0, 1.0);
    glVertex3f(1.0, 1.0, -1.0)
    glTexCoord2f(0.0, 1.0);
    glVertex3f(-1.0, 1.0, -1.0)
    glEnd()
    glDeleteTextures(1)


def load_and_draw_model():
    glLightfv(GL_LIGHT0, GL_POSITION, (-50, 200, 250, 0.0))
    glLightfv(GL_LIGHT0, GL_AMBIENT, (0.2, 0.2, 0.2, 1.0))
    glLightfv(GL_LIGHT0, GL_DIFFUSE, (0.5, 0.5, 0.5, 1.0))
    glEnable(GL_LIGHT0)
    glEnable(GL_LIGHTING)
    glEnable(GL_COLOR_MATERIAL)
    glEnable(GL_DEPTH_TEST)
    glShadeModel(GL_SMOOTH)
    obj = OBJ('./obj/cone.obj', swapyz=True)
    glCallList(obj.gl_list)

class DemoAR:
    def __init__(self, frame, glP, glM):
        self.frame = frame
        self.glP = glP
        self.glM = glM
        self.image = None
        # Init pygame.
        os.environ['SDL_VIDEO_WINDOW_POS'] = "%d,%d" % (1280, 800)
        pygame.init()
        self.screen = pygame.display.set_mode((384, 345),pygame.OPENGLBLIT | pygame.DOUBLEBUF)
        pygame.display.set_caption('pygame')
        # hwnd = self.GetHandle()
        # os.environ['SDL_WINDOWID'] = str(hwnd)

    def draw(self):
        drawBackground(self.frame)
        setProjection(self.glP)
        setModelview(self.glM, 5)
        load_and_draw_model()
        # get image buffer from opengl buffer
        buffer = glReadPixels(0, 0, 640, 576, GL_RGBA, GL_UNSIGNED_BYTE)
        self.image = Image.frombuffer(mode="RGBA", size=(640,576), data=buffer)
        #glutSwapBuffers()

    def main(self):
        self.draw()
        img = np.asarray(self.image)
        cv2.imshow('Handy AR', img)