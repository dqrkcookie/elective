# INSTALL PyQt5, matplotlib, numpy & scipy

import sys
from PyQt5.QtWidgets import (
    QApplication, QMainWindow, QLabel, QLineEdit,
    QPushButton, QGridLayout, QWidget, QMessageBox
)
from PyQt5.QtGui import QFont
from collections import Counter
from PyQt5.QtCore import Qt
import math
import matplotlib.pyplot as plt
from matplotlib.backends.backend_qt5agg import FigureCanvasQTAgg as FigureCanvas
import numpy as np
from scipy.stats import norm


class MainWindow(QMainWindow):
    def __init__(self):
        super().__init__()
        self.setWindowTitle("Geralyn Laput")
        self.setGeometry(500, 150, 1200, 700)

        central_widget = QWidget()
        self.setCentralWidget(central_widget)
        layout = QGridLayout()
        central_widget.setLayout(layout)

        self.input_field = QLineEdit()
        self.input_field.setPlaceholderText("Enter numbers separated by spaces (Dataset)")
        self.input_field.setStyleSheet("""
            QLineEdit {
                padding: 6px;
                font-size: 14px;
                font-family: Consolas;
                border: 2px solid green
            }
        """)
        layout.addWidget(self.input_field, 0, 0)

        self.result_label = QLabel("Result will appear here 😃")
        self.result_label.setAlignment(Qt.AlignCenter)
        self.result_label.setContentsMargins(20, 15, 20, 15)
        self.result_label.setFont(QFont("Consolas", 11))
        self.result_label.setStyleSheet("color: white; background-color: black; border: 2px solid gray")
        self.result_label.setWordWrap(True)
        layout.addWidget(self.result_label, 1, 0)

        # box for plotting statistics
        self.figure = plt.Figure(figsize=(8, 3.5), dpi=90)
        self.canvas = FigureCanvas(self.figure)

        layout.addWidget(self.canvas, 0, 1, 8, 1)

        # buttons + their corresponding statistical functions
        buttons = {
            "Mean": self.calculate_mean,
            "Median": self.calculate_median,
            "Mode": self.calculate_mode,
            "Range": self.calculate_range,
            "Variance": self.calculate_variance,
            "Standard Deviation": self.calculate_std_dev
        }

        num = 2;     
        for label, function in buttons.items():
            button = QPushButton(f"Calculate {label}")
            button.clicked.connect(function)
            button.setStyleSheet("font-family: Consolas; font-size: 14; padding: 4; border: 2px solid green;")
            layout.addWidget(button, num, 0)
            num+=1

    # accepts number separated by white spaces
    # splitting input numbers into list of strings
    # converts the result to a list
    # example 2 10 20 21 23 23 38 38
    # result [2, 10, 20, 21, 23, 23, 38, 38]
    def get_numbers(self):
        try:
            return list(map(int, self.input_field.text().split()))
        except ValueError:
            QMessageBox.warning(self, "Invalid Input", "Please enter valid numbers separated by spaces.")
            return []

    # mean = ∑xᵢ / n
    def calculate_mean(self):
        nums = self.get_numbers()
        if nums:
            total = sum(nums)
            count = len(nums)
            mean_value = total / count
            
            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += "Mean = ∑xᵢ / n<br><br>"
            steps += f"Mean = {total} / {count}<br><br>"
            steps += f"<span style='background-color: lightgreen; color: black;'>Mean = {mean_value:.2f}</span>" if mean_value != int(mean_value) else f"<span style='background-color: lightgreen; color: black;'>Mean = {int(mean_value)}</span>"
            
            self.result_label.setText(steps)
            self.plot_statistics(nums, mean_value)

    # # if odd ?? median = middle value of sorted data : median = (middle value 1 + middle value 2) / 2
    def calculate_median(self):
        nums = self.get_numbers()
        if nums:
            nums.sort()
            n = len(nums)
            mid = n // 2
            
            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += f"Sorted numbers: <br> {[num for num in nums]}<br><br>"
            
            if n % 2 == 0:
                median = (nums[mid-1] + nums[mid]) / 2
                steps += f"Median = ({nums[mid-1]} + {nums[mid]}) / 2 = {median:.2f}<br><br>"
            else:
                median = nums[mid]
                steps += f"Median = {median}<br><br>"
            
            steps += f"<span style='background-color: lightgreen; color: black;'>Median = {median:.2f}</span>"
            self.result_label.setText(steps)
            self.plot_statistics(nums, None, median)

    # mode = value(s) with the highest frequency
    def calculate_mode(self):
        nums = self.get_numbers()
        if nums:
            data = Counter(nums)
            max_count = max(data.values())
            modes = [num for num, count in data.items() if count == max_count]

            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += "Mode = Value(s) with highest frequency<br><br>"
            steps += "Frequencies:<br>"
            for num, count in sorted(data.items()):
                steps += f"{num}: {count}<br>"
            steps += f"<br>Most frequent = {max_count} times<br><br>"
            
            if len(modes) == len(data):
                steps += "All values appear equally often (no mode)"
            else:
                modes_str = [f"{mode}" if mode == int(mode) else f"{mode:.2f}" for mode in modes]
                steps += f"<span style='background-color: lightgreen; color: black;'>Mode = {', '.join(modes_str)}</span>"
            
            self.result_label.setText(steps)
            self.plot_statistics(nums, None, None, modes)

    # range = max value − min value
    def calculate_range(self):
        nums = self.get_numbers()
        if nums:
            smallest = min(nums)
            largest = max(nums)
            range_value = largest - smallest
            
            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += "Range = Max value - Min value<br><br>"
            steps += f"Range = {largest} - {smallest}<br><br>"
            steps += f"<span style='background-color: lightgreen; color: black;'>Range = {range_value}</span>"
            
            self.result_label.setText(steps)
            self.plot_statistics(nums, None, None, None, range_value)

    # variance = ∑(xᵢ−μ)² / n
    def calculate_variance(self):
        nums = self.get_numbers()
        if nums:
            mean = sum(nums) / len(nums)
            squared_diffs = [(x - mean) ** 2 for x in nums]
            sum_squared_diffs = sum(squared_diffs)
            variance = sum_squared_diffs / len(nums)
            
            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += "Variance = ∑(xᵢ - μ)² / n<br><br>"
            steps += f"μ = {mean:.2f}<br><br>"
            steps += "Squared differences:<br>"
            for i, x in enumerate(nums):
                steps += f"(x{i+1} - μ)² = ({x:.2f} - {mean:.2f})² = {(x - mean) ** 2:.2f}<br>"
            steps += f"<br>∑(xᵢ - μ)² = {sum_squared_diffs:.2f}<br><br>"
            steps += f"Variance = {sum_squared_diffs:.2f} / {len(nums)} = {variance:.2f}<br><br>"
            steps += f"<span style='background-color: lightgreen; color: black;'>Variance = {variance:.2f}</span>"
            
            self.result_label.setText(steps)
            self.plot_variance(squared_diffs, mean)

    # standard deviation = √variance
    def calculate_std_dev(self):
        nums = self.get_numbers()
        if nums:
            mean = sum(nums) / len(nums)
            squared_diffs = [(x - mean) ** 2 for x in nums]
            sum_squared_diffs = sum(squared_diffs)
            variance = sum_squared_diffs / len(nums)
            std_dev = math.sqrt(variance)
            
            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += "Standard Deviation = √Variance<br><br>"
            steps += f"μ = {mean:.2f}<br><br>"
            steps += "Squared differences:<br>"
            for i, x in enumerate(nums):
                steps += f"(x{i+1} - μ)² = ({x:.2f} - {mean:.2f})² = {(x - mean) ** 2:.2f}<br>"
            
            steps += f"<br>∑(xᵢ - μ)² = {sum_squared_diffs:.2f}<br><br>"
            steps += f"Variance = {sum_squared_diffs:.2f} / {len(nums)} = {variance:.2f}<br><br>"
            steps += f"<span style='background-color: lightgreen; color: black;'>Standard Deviation = √{variance:.2f} = {std_dev:.2f}</span>"
            
            self.result_label.setText(steps)
            self.plot_standard_deviation(nums, mean, std_dev)

    # graphical presentation
    def plot_statistics(self, nums, mean=None, median=None, modes=None, range_value=None, variance=None, std_dev=None):
        self.figure.clear()
        self.figure.subplots_adjust(left=0.1, right=0.9, top=0.85, bottom=0.15, hspace=0.4)
        ax = self.figure.add_subplot(111)
        ax.plot(nums, 'bo', label="Data points")
         
        # plotting datasets
        if mean is not None:
            ax.plot([0, len(nums) - 1], [mean, mean], color='green', linestyle='--', label=f'Mean: {mean:.2f}', linewidth=2)

        if median is not None:
            ax.plot([0, len(nums) - 1], [median, median], color='orange', linestyle='--', label=f'Median: {median:.2f}', linewidth=2)

        if modes is not None:
            for mode in modes:
                ax.plot([0, len(nums) - 1], [mode, mode], color='cyan', linestyle='--', label=f'Mode: {mode}', linewidth=2)

        if range_value is not None:
            ax.plot([0, len(nums) - 1], [min(nums), min(nums)], color='magenta', linestyle='--', label=f'Min: {min(nums)}', linewidth=2)
            ax.plot([0, len(nums) - 1], [max(nums), max(nums)], color='magenta', linestyle='--', label=f'Max: {max(nums)}', linewidth=2)

        ax.set_title("Statistical Visualization")
        ax.set_xlabel("Index")
        ax.set_ylabel("Value")

        ax.legend(loc='upper right')

        self.canvas.draw()

    def plot_standard_deviation(self, nums, mean, std_dev):
        self.figure.clear()
        ax = self.figure.add_subplot(111)

        # plot ±1 standard deviation lines and shaded area
        ax.axvline(x=mean + std_dev, color='purple', linestyle=':', linewidth=1, 
                    label=f'+1σ: {mean + std_dev:.2f}')
        ax.axvline(x=mean - std_dev, color='purple', linestyle=':', linewidth=1,
                    label=f'-1σ: {mean - std_dev:.2f}')
        ax.axvspan(mean - std_dev, mean + std_dev, alpha=0.3, color='blue', label='±1σ (68%)')

        # plot the mean line and normal distribution curve
        ax.axvline(x=mean, color='green', linestyle='--', linewidth=2, label=f'Mean: {mean:.2f}')
        ax2 = ax.twinx()
        x = np.linspace(min(nums) - 2 * std_dev, max(nums) + 2 * std_dev, 1000)
        y = norm.pdf(x, mean, std_dev)
        ax2.plot(x, y, 'r-', linewidth=2, label='Normal Distribution')
        ax2.fill_between(x, y, alpha=0.2, color='red')

        ax.set_title("Statistical Visualization")
        ax.set_xlabel("Value")

        # remove L y-axis labels
        ax.set_ylabel('')  
        ax.set_yticklabels([]) 
        ax.set_yticks([]) 
        # remove R y-axis labels
        ax2.set_ylabel('') 
        ax2.set_yticklabels([])  
        ax2.set_yticks([]) 

        lines, labels = ax.get_legend_handles_labels()
        lines2, labels2 = ax2.get_legend_handles_labels()
        ax.legend(lines + lines2, labels + labels2, loc='upper right')

        self.canvas.draw()

    def plot_variance(self, squared_diffs, mean):
        self.figure.clear()
        ax = self.figure.add_subplot(111)

        ax.bar(range(len(squared_diffs)), squared_diffs, color='orange', alpha=0.7, label='Squared Differences')
        ax.axhline(mean, color='g', linestyle='--', label=f'Mean: {mean:.2f}', linewidth=2)

        ax.set_title("Statistical Visualization")
        ax.set_xlabel("Data Index")
        ax.set_ylabel("Squared Difference Value")
        ax.legend()

        self.canvas.draw()

def main():
    app = QApplication(sys.argv)
    window = MainWindow()
    window.show()
    sys.exit(app.exec_())

if __name__ == '__main__':
    main()