"""
 It's me, Mohamad Shaninah and that's my final project for my apprenticeship
 When I wrote this, only God and I understood what I was doing
 Now probably only God knows
 In case that someone else is reading this, have fun understanding my code
 Finally, it sucks that Python doesn't support multi-line comment blocks. That's why I'm using
 string literals that are not assigned to a variable
"""
# Importing Libraries

import cv2
import mediapipe as mp
import numpy as np

# Drawing utilities to visualize the Poses
mpDrawing = mp.solutions.drawing_utils

# Pose estimation Model
mpPose = mp.solutions.pose

# Capturing the video using the Webcam, the number 0 is representing the webcam in the function
cap = cv2.VideoCapture(0)

# Curl Counter
counter = 0
stage = None

""" Setup mediapipe instance """

# I'm using "with" keyword because I'm working with unmanaged resource (Webcam Stream).
with mpPose.Pose(min_detection_confidence=0.9, min_tracking_confidence=0.9) as pose:
    # As long as the Video Feed is open, this loop will read the feed from the Webcam and save it in the variable
    # "frame" as a video feed
    while cap.isOpened():
        ret, frame = cap.read()

        """ Detection and rendering """

        # Recolor the image to RGB to pass it to the Mediapipe
        image = cv2.cvtColor(frame, cv2.COLOR_BGR2RGB)

        # Making the image not writable to save memory
        image.flags.writeable = False

        # Make detection
        results = pose.process(image)
        image.flags.writeable = True

        # Recolor the image back to BGR to pass it to the OpenCV
        image = cv2.cvtColor(image, cv2.COLOR_RGB2BGR)

        """
        a function to calculate the angle between Wrist, Elbow and Shoulder 
        """
        def calculate_angle(a, b, c):
            a = np.array(a)  # First point "the Wrist"
            b = np.array(b)  # Middle point "the Elbow"
            c = np.array(c)  # End Point "the Shoulder"

            radians = np.arctan2(c[1] - b[1], c[0] - b[0]) - np.arctan2(a[1] - b[1], a[0] - b[0])
            angle = np.abs(radians * 180.0 / np.pi)

            if angle > 180.0:
                angle = 360 - angle

            return angle

        # Extracting landmarks using try and except block for the case the landmarks are not able to be extracted or
        # in case the webcam fade
        try:
            landmarks = results.pose_landmarks.landmark

            # Get coordinates
            shoulder = [landmarks[mpPose.PoseLandmark.LEFT_SHOULDER.value].x,
                        landmarks[mpPose.PoseLandmark.LEFT_SHOULDER.value].y]
            elbow = [landmarks[mpPose.PoseLandmark.LEFT_ELBOW.value].x,
                     landmarks[mpPose.PoseLandmark.LEFT_ELBOW.value].y]
            wrist = [landmarks[mpPose.PoseLandmark.LEFT_WRIST.value].x,
                     landmarks[mpPose.PoseLandmark.LEFT_WRIST.value].y]

            # Calculate angle
            angle = calculate_angle(shoulder, elbow, wrist)

            # Visualize angle
            cv2.putText(image, str(angle),
                        tuple(np.multiply(elbow, [640, 480]).astype(int)),
                        cv2.FONT_HERSHEY_SIMPLEX, 0.5, (255, 255, 255), 2, cv2.LINE_AA
                        )

            # Curl Counter logic
            if angle > 160:
                stage = "down"
            if angle < 30 and stage == "down":
                stage = "up"
                counter += 1
                print(counter)
        except:
            pass

        """ Render curl counter """

        # Setup Status box
        # the First Parameter is the Current rendered Image, the Second one is the Start Point for the Rectangle
        # the Third is the Endpoint for the Rectangle, Forth is Color, and The Last One to Fill the Box with Color
        cv2.rectangle(image, (0, 0), (225, 73), (245, 117, 16), -1)

        """ Representing Data """
        cv2.putText(image, 'Represented Data', (15, 12), cv2.FONT_HERSHEY_SIMPLEX, 0.5, (0, 0, 0), 1, cv2.LINE_AA)
        cv2.putText(image, str(counter), (10, 60), cv2.FONT_HERSHEY_SIMPLEX, 2, (255, 255, 255), 2, cv2.LINE_AA)

        """ Render detection """
        mpDrawing.draw_landmarks(image, results.pose_landmarks, mpPose.POSE_CONNECTIONS,
                                 mpDrawing.DrawingSpec(color=(245, 117, 66), thickness=2, circle_radius=2),
                                 mpDrawing.DrawingSpec(color=(245, 66, 230), thickness=2, circle_radius=2))

        # cv2 will show a popup on the screen that allow the image visualizing.
        # the first parameter is the name of the frame, and the second one is the frame itself
        cv2.imshow('Mediapipe Feed', image)

        """ Clear the feed using the key 'q', or by closing the popup """
        if cv2.waitKey(10) & 0xFF == ord('q') or cv2.getWindowProperty('Mediapipe Feed', cv2.WND_PROP_VISIBLE) < 1:
            break

cap.release()
cv2.destroyAllWindows()
