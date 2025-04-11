# INSTALL PyQt5, matplotlib, numpy & scipy

import sys
from PyQt5.QtWidgets import (
    QApplication, QMainWindow, QLabel, QLineEdit,
    QPushButton, QGridLayout, QWidget, QMessageBox, QRadioButton
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

        self.radio_button1 = QRadioButton("Population (For variance and standard deviation)")
        self.radio_button2 = QRadioButton("Sample (For variance and standard deviation)")
        layout.addWidget(self.radio_button1, 1, 0)
        layout.addWidget(self.radio_button2, 2, 0)

        self.result_label = QLabel("Result will appear here 😃")
        self.result_label.setAlignment(Qt.AlignCenter)
        self.result_label.setContentsMargins(20, 15, 20, 15)
        self.result_label.setFont(QFont("Consolas", 11))
        self.result_label.setStyleSheet("color: white; background-color: black; border: 2px solid gray")
        self.result_label.setWordWrap(True)
        layout.addWidget(self.result_label, 3, 0)

        # box for plotting statistics
        self.figure = plt.Figure(figsize=(8, 3.5), dpi=90)
        self.canvas = FigureCanvas(self.figure)
        layout.addWidget(self.canvas, 0, 1, 10, 1)

        # buttons + their corresponding statistical functions
        buttons = {
            "Mean": self.calculate_mean,
            "Median": self.calculate_median,
            "Mode": self.calculate_mode,
            "Range": self.calculate_range,
            "Variance": self.calculate_variance,
            "Standard Deviation": self.calculate_std_dev
        }

        num = 4;     
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
            joined_nums = " + ".join(str(i) for i in nums)
            total = sum(nums)
            count = len(nums)
            mean_value = total / count
            
            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += "Mean = ∑xᵢ / n<br><br>"
            steps += f"Mean = {joined_nums} / {count}<br><br>"
            steps += f"Mean = {total} / {count}<br><br>"
            steps += f"<span style='background-color: lightgreen; color: black;'>| Mean = {mean_value:.2f} |</span>" if mean_value != int(mean_value) else f"<span style='background-color: lightgreen; color: black;'>| Mean = {int(mean_value)} |</span>"
            
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
            
            steps += f"<span style='background-color: lightgreen; color: black;'>| Median = {median} |</span>"
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
                steps += f"<span style='background-color: lightgreen; color: black;'>| Mode = {', '.join(modes_str)} |</span>"
            
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
            steps += f"<span style='background-color: lightgreen; color: black;'>| Range = {range_value} |</span>"
            
            self.result_label.setText(steps)
            self.plot_statistics(nums, None, None, None, range_value)

    # variance = ∑(xᵢ−μ)² / n
    def calculate_variance(self):
        nums = self.get_numbers()
        if nums:
            mean = sum(nums) / len(nums)
            squared_diffs = [(x - mean) ** 2 for x in nums]
            sum_squared_diffs = sum(squared_diffs)

            if self.radio_button1.isChecked():  
                variance = sum_squared_diffs / len(nums)
                formula_label = "Population Variance = ∑(xᵢ - μ)² / n"
                divisor = f"{len(nums)}"
            elif self.radio_button2.isChecked(): 
                if len(nums) < 2:
                    self.result_label.setText("<font color='red'>Sample variance requires at least 2 data points.</font>")
                    return
                variance = sum_squared_diffs / (len(nums) - 1)
                formula_label = "Sample Variance = ∑(xᵢ - x̄)² / (n - 1)"
                divisor = f"{len(nums)} - 1"
            else:
                self.result_label.setText("<font color='red'>Please select Population or Sample.</font>")
                return

            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += formula_label + "<br><br>"
            steps += f"Mean = {mean:.2f}<br><br>"
            steps += "Squared differences:<br>"
            for i, x in enumerate(nums):
                steps += f"(x{i+1} - x̄)² = ({x:.2f} - {mean:.2f})² = {(x - mean) ** 2:.2f}<br>"
            steps += f"<br>∑(xᵢ - x̄)² = {sum_squared_diffs:.2f}<br><br>"
            steps += f"Variance = {sum_squared_diffs:.2f} / {divisor} = {variance:.2f}<br><br>"
            steps += f"<span style='background-color: lightgreen; color: black;'>| Variance = {variance:.2f} |</span>"

            self.result_label.setText(steps)
            self.plot_variance(squared_diffs, mean)

    # standard deviation = √variance
    def calculate_std_dev(self):
        nums = self.get_numbers()
        if nums:
            mean = sum(nums) / len(nums)
            squared_diffs = [(x - mean) ** 2 for x in nums]
            sum_squared_diffs = sum(squared_diffs)

            if self.radio_button1.isChecked():  
                variance = sum_squared_diffs / len(nums)
                label = "Population Standard Deviation = √(∑(xᵢ - μ)² / n)"
                divisor = f"{len(nums)}"
            elif self.radio_button2.isChecked():
                if len(nums) < 2:
                    self.result_label.setText("<font color='red'>Sample standard deviation requires at least 2 data points.</font>")
                    return
                variance = sum_squared_diffs / (len(nums) - 1)
                label = "Sample Standard Deviation = √(∑(xᵢ - x̄)² / (n - 1))"
                divisor = f"{len(nums)} - 1"
            else:
                self.result_label.setText("<font color='red'>Please select Population or Sample.</font>")
                return

            std_dev = math.sqrt(variance)

            steps = "<font style='font-weight: 600; color: green;'>Solution:</font><br><br>"
            steps += label + "<br><br>"
            steps += f"Mean = {mean:.2f}<br><br>"
            steps += "Squared differences:<br>"
            for i, x in enumerate(nums):
                steps += f"(x{i+1} - x̄)² = ({x:.2f} - {mean:.2f})² = {(x - mean) ** 2:.2f}<br>"
            steps += f"<br>∑(xᵢ - x̄)² = {sum_squared_diffs:.2f}<br><br>"
            steps += f"Variance = {sum_squared_diffs:.2f} / {divisor} = {variance:.2f}<br><br>"
            steps += f"<span style='background-color: lightgreen; color: black;'>| Standard Deviation = √{variance:.2f} = {std_dev:.2f} |</span>"

            self.result_label.setText(steps)
            self.plot_standard_deviation(nums, mean, std_dev)

    # graphical presentation
    def plot_statistics(self, nums, mean=None, median=None, modes=None, range_value=None, variance=None, std_dev=None):
        self.figure.clear()
        self.figure.subplots_adjust(left=0.1, right=0.9, top=0.85, bottom=0.15, hspace=0.4)
        ax = self.figure.add_subplot(111)     
        ax.plot(nums, c='gray', label='Dataset')

        # plotting datasets
        if mean is not None:
            ax.plot([0, len(nums) - 1], [mean, mean], c='green', linestyle='--', label=f'Mean: {mean:.2f}', linewidth=2)

        if median is not None:
            ax.plot([0, len(nums) - 1], [median, median], c='green', linestyle='--', label=f'Median: {median:.2f}', linewidth=2)

        if modes is not None:
            for mode in modes:
                ax.plot([0, len(nums) - 1], [mode, mode], c='green', linestyle='--', label=f'Mode: {mode}', linewidth=2)

        if range_value is not None:
            ax.plot([0, len(nums) - 1], [range_value, range_value], c='blue', linestyle='-', label=f'Range: {range_value}', linewidth=2, alpha=0.5)
            ax.plot([0, len(nums) - 1], [min(nums), min(nums)], c='green', linestyle='--', label=f'Min: {min(nums)}', linewidth=2)
            ax.plot([0, len(nums) - 1], [max(nums), max(nums)], c='green', linestyle='--', label=f'Max: {max(nums)}', linewidth=2)

        ax.set_title("Statistical Visualization")
        ax.set_xlabel("Data Index")
        ax.set_ylabel("Value")

        ax.legend(loc='upper right')

        self.canvas.draw()

    def plot_standard_deviation(self, nums, mean, std_dev):
        self.figure.clear()
        ax = self.figure.add_subplot(111)

        # plot mean and ±1σ lines with shaded area
        ax.axvline(mean, color='green', linestyle='--', linewidth=2, label=f'Mean: {mean:.2f}')
        for offset, label in [(std_dev, '+1σ'), (-std_dev, '-1σ')]:
            ax.axvline(mean + offset, color='darkgreen', linestyle=':', linewidth=1, label=f'{label}: {mean + offset:.2f}')
        ax.axvspan(mean - std_dev, mean + std_dev, alpha=0.3, color='blue', label='±1σ (68%)')

        # bell curve
        ax2 = ax.twinx()
        x = np.linspace(min(nums) - 2 * std_dev, max(nums) + 2 * std_dev, 1000)
        y = norm.pdf(x, mean, std_dev)
        ax2.plot(x, y, 'y-', linewidth=2, label='Bell Curve')
        ax2.fill_between(x, y, alpha=0.2, color='gray')

        ax.set_title("Statistical Visualization")
        ax.set_xlabel("Value")
        for axis in (ax, ax2):
            axis.set_ylabel('')
            axis.set_yticks([])
            axis.set_yticklabels([])

        lines, labels = ax.get_legend_handles_labels()
        lines2, labels2 = ax2.get_legend_handles_labels()
        ax.legend(lines + lines2, labels + labels2, loc='upper right')

        self.canvas.draw()

    def plot_variance(self, squared_diffs, mean):
        self.figure.clear()
        ax = self.figure.add_subplot(111)

        ax.bar(range(len(squared_diffs)), squared_diffs, color='gray', alpha=0.7, label='Squared Differences')
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