#ifndef UTILS_H_
#define UTILS_H_

#include <string>
#include <vector>

// Returns the name of the executable.
std::string GetExecutableName();

// Returns the directory containing the executable.
std::string GetExecutableDirectory();

// Returns the command line arguments passed to the executable.
std::vector<std::string> GetCommandLineArguments();

#endif
